@api
Feature: Contacts
  In order to manage contacts
  I need to be able to  retrieve, create, update and delete through the API.
  Scenario Outline: Create a new contact
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/contact" with body:
    """
    {
      "name": "<name>",
      "emails": [
        {
          "email": "<email>",
          "label": "<emailLabel>"
        }
      ],
       "streetAndNumber": "<streetAndNumber>",
       "postcode": "<postcode>",
       "city": "<city>",
       "country": "<country>",
       "phoneNumbers": [
          {
            "phone": "<phone>",
            "label": "<phoneLabel>"
          }
        ]
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON nodes should contain:
      | name                  | <name>              |
      | emails[0].email       | <email>             |
      | emails[0].label       | <emailLabel>        |
      | streetAndNumber       | <streetAndNumber>   |
      | postcode              | <postcode>          |
      | city                  | <city>              |
      | country               | <country>           |
      | phoneNumbers[0].phone | <phone>             |
      | phoneNumbers[0].label | <phoneLabel>        |
    Examples:
      | name          | email             | emailLabel | streetAndNumber           | postcode | city    | country | phone           | phoneLabel |
      | Jon Doe      | email@example.org | Work       | ul. Poziomkowa 12a m. 24  | 12-212   | Kraków   | Poland  | +48 712 147 147 | Work       |
      | Jon Kowalski | kowal@example.org | Home       | ul. Słowacka 1            | 11-111   | Warszawa | Poland  | +48 123 147 147 | Work       |
    Scenario Outline: Update a existing contacts
      Given there are following contacts
        | uuid   | name          |
        | <uuid> | <name>        |
         And I add "Content-Type" header equal to "application/json"
      And I add "Accept" header equal to "application/json"
      And I send a "PUT" request to "/api/contact/<uuid>" with body:
      """
      {
        "name": "<NewName>",
        "emails": [
          {
            "email": "<email>",
            "label": "<emailLabel>"
          }
        ],
         "streetAndNumber": "<streetAndNumber>",
         "postcode": "<postcode>",
         "city": "<city>",
         "country": "<country>",
         "phoneNumbers": [
            {
              "phone": "<phone>",
              "label": "<phoneLabel>"
            }
          ]
      }
      """
      Then the response status code should be 200
      And the response should be in JSON
      And the JSON nodes should contain:
        | uuid                  | <uuid>              |
        | name                  | <NewName>           |
        | emails[0].email       | <email>             |
        | emails[0].label       | <emailLabel>        |
        | streetAndNumber       | <streetAndNumber>   |
        | postcode              | <postcode>          |
        | city                  | <city>              |
        | country               | <country>           |
        | phoneNumbers[0].phone | <phone>             |
        | phoneNumbers[0].label | <phoneLabel>        |
      Examples:
        | uuid                                   | name     | NewName      | email             | emailLabel | streetAndNumber           | postcode | city    | country | phone           | phoneLabel |
        | 6bf93644-b326-4023-8dc9-32c301afaa7d   | test     | Jon Doe      | email@example.org | Work       | ul. Poziomkowa 12a m. 24  | 12-212   | Kraków   | Poland  | +48 712 147 147 | Work       |
        | b34872fe-8016-44ff-9b77-9ae1e0eaaad8   | test2    | Jon Kowalski | kowal@example.org | Home       | ul. Słowacka 1            | 11-111   | Warszawa | Poland  | +48 123 147 147 | Work       |

  Scenario Outline: get a existing contacts
    Given there are following contacts
      | uuid   | name          |
      | <uuid> | <name>        |
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/contact/<uuid>"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON nodes should contain:
      | uuid                      | <uuid>                |
      | name                      | <name>                |
    Examples:
      | uuid                                 | name     |
      | 6bf93644-b326-4023-8dc9-32c301afaa7d | test     |
      | b34872fe-8016-44ff-9b77-9ae1e0eaaad8 | test2    |

  Scenario Outline: remove a existing contacts
    Given there are following contacts
      | uuid   | name          |
      | <uuid> | <name>        |
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "DELETE" request to "/api/contact/<uuid>"
    Then the response status code should be 204
    And the response should be empty
    Then I send a "GET" request to "/api/contact/<uuid>"
    And the response status code should be 404
    Examples:
      | uuid                                 | name     |
      | 6bf93644-b326-4023-8dc9-32c301afaa7d | test     |
      | b34872fe-8016-44ff-9b77-9ae1e0eaaad8 | test2    |