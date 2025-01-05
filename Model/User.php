<?php
namespace Model;
use JsonSerializable;

class User implements JsonSerializable {
    private $username;
    private $firstName;
    private $lastName;
    private $coffeeOrTea;
    private $description;
    private $chatLayout;
    private $changeHistory = array();
    private $status; // Add this for friend status if needed
    private $unread; // Add this for unread messages if needed

    public function __construct($username = null) {
        $this->username = $username;
    }

    // Getters and Setters
    public function getUsername() {
        return $this->username;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getCoffeeOrTea() {
        return $this->coffeeOrTea;
    }

    public function setCoffeeOrTea($preference) {
        $this->coffeeOrTea = $preference;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getChatLayout() {
        return $this->chatLayout;
    }

    public function setChatLayout($layout) {
        $this->chatLayout = $layout;
    }

    public function getChangeHistory() {
        return $this->changeHistory;
    }

    public function addToHistory() {
        $this->changeHistory[] = date('Y-m-d H:i:s');
    }

    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }

    public static function fromJson($data) {
        $user = new User();
        // Only set properties that are declared in the class
        foreach ($data as $key => $value) {
            if (property_exists($user, $key)) {
                $user->{$key} = $value;
            }
        }
        return $user;
    }
}
