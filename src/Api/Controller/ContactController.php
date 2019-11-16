<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\DTO\ContactGetDTO;
use App\Api\DTO\ContactPostDTO;
use App\Api\DTO\ContactPutDTO;
use App\Api\Transformer\ContactTransformer;
use App\Entity\Contact;
use App\Manager\ContactManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractFOSRestController
{
    /** @var ContactTransformer */
    private $contactTransformer;
    /** @var ContactManager */
    private $contactManager;

    public function __construct(ContactTransformer $contactTransformer, ContactManager $contactManager)
    {
        $this->contactTransformer = $contactTransformer;
        $this->contactManager     = $contactManager;
    }

    /**
     * List Contacts
     *
     * @SWG\Get(
     *     tags={"Contact"},
     *     description="List Contacts",
     *     @SWG\Response(
     *         response="200",
     *         description="Contacts list"
     *    )
     * )
     * @Rest\Get("contact")
     */
    public function getListAction() : Response
    {
        return $this->handleView(
            $this->view([], Response::HTTP_NO_CONTENT)
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
     *          description="Invalid arguments"
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
    public function postAction(ContactPostDTO $contactDTO) : Response
    {
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
     *          description="Invalid arguments"
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
    public function putAction(ContactPutDTO $contactDTO, Contact $contact) : Response
    {
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
        $this->contactManager->remove($contact);
        $this->contactManager->flush();

        return $this->handleView(
            $this->view(null, Response::HTTP_NO_CONTENT)
        );
    }

    private function handleUpdate(Contact $contact, ?int $statusCode = null) : Response
    {
        $this->contactManager->update($contact);
        $dto = $this->contactTransformer->modelToDto($contact);

        return $this->handleView(
            $this->view($dto, $statusCode)
        );
    }
}
