@api
Feature: Contacts Validation
  I want validation to work properly
  And I will check it here
  Scenario: Test invalid email
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/contact" with body:
    """
    {
      "name": "some name",
      "emails": [
        {
          "value": "wrong email",
          "label": "some label"
        }
      ]
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON nodes should contain:
      | error.code                       | 400             |
      | error.violations[0].propertyPath | emails[0].value |
    And the JSON node "error.violations" should have 1 element

  Scenario: Test duplicated contact
    Given there are following contacts
      | name |
      | some name |
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/contact" with body:
    """
    {
      "name": "some name"
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON nodes should contain:
      | error.code                       | 400  |
      | error.violations[0].propertyPath | name |
    And the JSON node "error.violations" should have 1 element
  Scenario: Test duplicated contact with PUT
    Given there are following contacts
      | uuid | name |
      | 6bf93644-b326-4023-8dc9-32c301afaa7d | some name |
      | b34872fe-8016-44ff-9b77-9ae1e0eaaad8 | some name 2|
    And I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/contact/b34872fe-8016-44ff-9b77-9ae1e0eaaad8" with body:
    """
    {
      "name": "some name"
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON nodes should contain:
      | error.code                       | 400  |
      | error.violations[0].propertyPath | name |
    And the JSON node "error.violations" should have 1 element
