<?php

declare(strict_types=1);

namespace App\Contact\Infrastructure\Api;

use App\Contact\Application\DTO\ContactGetDTO;
use App\Contact\Application\DTO\ContactPostDTO;
use App\Contact\Application\DTO\ContactPutDTO;
use App\Contact\Application\DTO\ContactsGetDTO;
use App\Contact\Application\Transformer\ContactTransformer;
use App\Contact\Domain\Model\Contact;
use App\Contact\Infrastructure\Manager\ContactManager;
use App\Contact\Infrastructure\Repository\ContactRepository;
use App\Shared\Application\DTO\Error\BadRequestDTO;
use App\Shared\Application\Transformer\ConstraintViolationTransformer;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use function count;

class ContactController extends AbstractFOSRestController
{
    /** @var ContactTransformer */
    private $contactTransformer;
    /** @var ConstraintViolationTransformer */
    private $violationTransformer;
    /** @var ContactManager */
    private $manager;

    /** @var ContactRepository */
    private $repository;

    public function __construct(
        ContactTransformer $contactTransformer,
        ConstraintViolationTransformer $violationTransformer,
        ContactManager $contactManager,
        ContactRepository $contactRepository
    ) {
        $this->contactTransformer   = $contactTransformer;
        $this->violationTransformer = $violationTransformer;
        $this->manager              = $contactManager;
        $this->repository           = $contactRepository;
    }

    /**
     * List Contacts
     *
     * @SWG\Get(
     *     tags={"Contact"},
     *     description="List Contacts",
     *     @SWG\Response(
     *         response="200",
     *         description="Contacts list",
     *         @Model(type=ContactsGetDTO::class)
     *    )
     * )
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default=1,
     *     description="Results page"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default=100,
     *     description="Limit results per page"
     * )
     * @Rest\Get("contact")
     */
    public function getListAction(int $page, int $limit) : Response
    {
        $paginator = $this->repository->createPaginator($page, $limit);

        $list = $this->contactTransformer->modelsToDto($paginator);

        return $this->handleView(
            $this->view($list, Response::HTTP_OK)
        );
    }

    /**
     * Get contact
     *
     *  @SWG\Get(
     *     tags={"Contact"},
     *     description="Contact details",
     *     @SWG\Response(
     *          response="200",
     *          description="Contact details",
     *          @Model(type=ContactGetDTO::class)
     *     ),
     *     @SWG\Response(
     *          response="404",
     *          description="Contact not found"
     *     )
     * )
     * @Rest\Get("contact/{uuid}")
     * @SWG\Parameter(
     *     name="uuid",
     *     type="string",
     *     in="path",
     *     required=true
     * )
     */
    public function getAction(Contact $contact) : Response
    {
        $dto = $this->contactTransformer->modelToDto($contact);

        return $this->handleView(
            $this->view($dto, Response::HTTP_OK)
        );
    }

    /**
     * Create a new contact
     *
     *  @SWG\Post(
     *     tags={"Contact"},
     *     description="Create contact",
     *     @SWG\Response(
     *          response="201",
     *          description="New contact details",
     *          @Model(type=ContactGetDTO::class)
     *     ),
     *     @SWG\Response(
     *          response="400",
     *          description="Invalid arguments",
     *          @Model(type=BadRequestDTO::class)
     *     )
     * )
     * @Rest\Post("contact")
     * @SWG\Parameter(
     *     name="json",
     *     type="string",
     *     in="body",
     *     required=true,
     *     @Model(type=ContactPostDTO::class)
     * )
     * @ParamConverter("contactDTO", converter="fos_rest.request_body")
     */
    public function postAction(
        ContactPostDTO $contactDTO,
        ConstraintViolationListInterface $validationErrors
    ) : Response {
        if (count($validationErrors)) {
            return $this->handleValidationErrors($validationErrors);
        }

        $contact = $this->contactTransformer->postDtoToModel($contactDTO);

        return $this->handleUpdate($contact, Response::HTTP_CREATED);
    }

    /**
     * Update a contact
     *
     *  @SWG\Put(
     *     tags={"Contact"},
     *     description="Update contact",
     *     @SWG\Response(
     *          response="200",
     *          description="Updated contact details",
     *          @Model(type=ContactGetDTO::class)
     *     ),
     *     @SWG\Response(
     *          response="400",
     *          description="Invalid arguments",
     *          @Model(type=BadRequestDTO::class)
     *     ),
     *     @SWG\Response(
     *          response="404",
     *          description="Contact not found"
     *     )
     * )
     * @Rest\Put("contact/{uuid}")
     * @SWG\Parameter(
     *     name="json",
     *     type="string",
     *     in="body",
     *     required=true,
     *     @Model(type=ContactPutDTO::class)
     * )
     * @ParamConverter("contactDTO", converter="fos_rest.request_body")
     */
    public function putAction(
        ContactPutDTO $contactDTO,
        Contact $contact,
        ConstraintViolationListInterface $validationErrors
    ) : Response {
        if (count($validationErrors)) {
            return $this->handleValidationErrors($validationErrors);
        }
        $contact = $this->contactTransformer->putDtoToModel($contactDTO, $contact);

        return $this->handleUpdate($contact, Response::HTTP_OK);
    }

    /**
     * Delete a contact
     *
     *  @SWG\Delete(
     *     tags={"Contact"},
     *     description="Delete contact",
     *     @SWG\Response(
     *          response="204",
     *          description="No content"
     *     ),
     *     @SWG\Response(
     *          response="400",
     *          description="Invalid arguments"
     *     ),
     *     @SWG\Response(
     *          response="404",
     *          description="Contact not found"
     *     )
     * )
     * @Rest\Delete("contact/{uuid}")
     */
    public function deleteAction(Contact $contact) : Response
    {
        $this->manager->remove($contact);
        $this->manager->flush();

        return $this->handleView(
            $this->view(null, Response::HTTP_NO_CONTENT)
        );
    }

    private function handleUpdate(Contact $contact, ?int $statusCode = null) : Response
    {
        $this->manager->update($contact);
        $dto = $this->contactTransformer->modelToDto($contact);

        return $this->handleView(
            $this->view($dto, $statusCode)
        );
    }

    protected function handleValidationErrors(ConstraintViolationListInterface $validationErrors) : Response
    {
        $error = $this->violationTransformer->violationsToDto($validationErrors);

        return $this->handleView($this->view($error, Response::HTTP_BAD_REQUEST));
    }
}
