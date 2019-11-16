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
          "value": "<email>",
          "label": "<emailLabel>"
        }
      ],
       "streetAndNumber": "<streetAndNumber>",
       "postcode": "<postcode>",
       "city": "<city>",
       "country": "<country>",
       "phoneNumbers": [
          {
            "value": "<phone>",
            "label": "<phoneLabel>"
          }
        ]
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON nodes should contain:
      | name                  | <name>              |
      | emails[0].value       | <email>             |
      | emails[0].label       | <emailLabel>        |
      | streetAndNumber       | <streetAndNumber>   |
      | postcode              | <postcode>          |
      | city                  | <city>              |
      | country               | <country>           |
      | phoneNumbers[0].value | <phone>             |
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
            "value": "<email>",
            "label": "<emailLabel>"
          }
        ],
         "streetAndNumber": "<streetAndNumber>",
         "postcode": "<postcode>",
         "city": "<city>",
         "country": "<country>",
         "phoneNumbers": [
            {
              "value": "<phone>",
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
        | emails[0].value       | <email>             |
        | emails[0].label       | <emailLabel>        |
        | streetAndNumber       | <streetAndNumber>   |
        | postcode              | <postcode>          |
        | city                  | <city>              |
        | country               | <country>           |
        | phoneNumbers[0].value | <phone>             |
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
  Scenario Outline: remove a email from contact
    Given there are following contacts
      | uuid   | name          | email1   | email2   |
      | <uuid> | <name>        | <email1> | <email2> |
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/contact/<uuid>" with body:
    """
      {
        "name" : "<name>",
        "emails": [
          {
            "value": "<email2>"
          }
        ]
      }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "emails" should have 1 element
    And the JSON nodes should contain:
      | uuid                  | <uuid>               |
      | name                  | <name>               |
      | emails[0].value       | <email2>             |
    Examples:
      | uuid                                 | name     | email1        |   email2       |
      | 6bf93644-b326-4023-8dc9-32c301afaa7d | test     | a@example.org | vv@example.org |
      | b34872fe-8016-44ff-9b77-9ae1e0eaaad8 | test2    | c@example.org | ss@example.org |
  Scenario Outline: remove a phone number from contact
    Given there are following contacts
      | uuid   | name          | phone1   | phone2   |
      | <uuid> | <name>        | <phone1> | <phone2> |
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/contact/<uuid>" with body:
    """
      {
        "name" : "<name>",
        "phoneNumbers": [
          {
            "value": "<phone2>"
          }
        ]
      }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "phoneNumbers" should have 1 element
    And the JSON nodes should contain:
      | uuid                  | <uuid>               |
      | name                  | <name>               |
      | phoneNumbers[0].value       | <phone2>       |
    Examples:
      | uuid                                 | name     | phone1          |   phone2           |
      | 6bf93644-b326-4023-8dc9-32c301afaa7d | test     | +48 123 541 212 | +48 123 123 212    |
      | b34872fe-8016-44ff-9b77-9ae1e0eaaad8 | test2    | +48 123 321 212 | +48 123 654 212    |
