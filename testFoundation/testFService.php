<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Entity\EAddress;
use Entity\ELocation;
use Entity\EOwner;
use Entity\EService;
use Foundation\FDatabase;
use Foundation\FLocation;
use Foundation\FService;

function getTestOwnerData(): EOwner
{
    return new EOwner(
        null, // ID will be set on insert
        'testuser' . uniqid(), // Genera username univoco
        'Test',
        'Owner',
        new DateTime('2000-01-01'), // Birthdate
        '1234567890', // Phone
        'https://example.com/image.jpg', // Image URL
        'testowner@example.com', // Email
        'securePassword@123', // Password
        0 // Not banned
    );
}

/**
 * @throws Exception
 */
function insertTestOwner(): int
{
    $db = FDatabase::getInstance();
    $testOwner = getTestOwnerData();

    $data = [
        'username' => $testOwner->getUsername(),
        'name' => $testOwner->getName(),
        'surname' => $testOwner->getSurname(),
        'birthDate' => $testOwner->getBirthDate()->format('Y-m-d'),
        'phone' => $testOwner->getPhone(),
        'image' => $testOwner->getImage(),
        'email' => $testOwner->getEmail(),
        'password' => password_hash($testOwner->getPassword(), PASSWORD_DEFAULT),
        'validation' => $testOwner->getValidation()
    ];

    $ownerId = $db->insert('owner', $data);
    if ($ownerId === null) {
        throw new Exception('Error inserting the owner.');
    }

    return $ownerId;
}

function getTestAddressData(): EAddress
{
    return new EAddress(
        '123 Test Street', // Street Address
        '10', // Civic Number
        'Test City', // City
        '12345', // Postal Code
        'Test Country', // Country
        'Test State', // State
        null
    );
}

/**
 * @throws Exception
 */
function insertTestAddress(): int
{
    $db = FDatabase::getInstance();
    $testAddress = getTestAddressData();

    $data = [
        'streetAddress' => $testAddress->getStreetAddress(),
        'civicNumber' => $testAddress->getCivicNumber(),
        'city' => $testAddress->getCity(),
        'postalCode' => $testAddress->getPostalCode(),
        'country' => $testAddress->getCountry(),
        'state' => $testAddress->getState(),
    ];

    $addressId = $db->insert('address', $data);
    if ($addressId === null) {
        throw new Exception('Error inserting the address.');
    }

    return $addressId;
}

function getTestLocationData(): array
{
    return [
        'name' => 'Test Location',
        'VATNumber' => 'IT12345678901',
        'description' => 'A test location for reviews.',
        'photo' => 'https://example.com/location.jpg',
        'type' => 'Restaurant',
        'idOwner' => insertTestOwner(), // Insert test owner and use their ID
        'idAddress' => insertTestAddress() // Insert a test address and use its ID
    ];
}

/**
 * Funzione per inserire una location di test nel database e restituire l'ID.
 * @throws Exception
 */
function insertTestLocation(): int
{
    $fLocation = new FLocation();
    $testLocation = getTestLocationData();

    $location = new ELocation(
        null,
        $testLocation['name'],
        $testLocation['VATNumber'],
        $testLocation['description'],
        (array)$testLocation['photo'], // Foto come array
        $testLocation['type'],
        $testLocation['idOwner'],
        $testLocation['idAddress']
    );

    $locationId = $fLocation->storeLocation($location);
    if ($locationId === null) {
        throw new Exception('Errore durante l\'inserimento della location.');
    }

    return $locationId;
}

/**
 * Function to create test data for EService.
 */
function getTestServiceData(): EService
{
    return new EService(null, "Test Service");
}

/**
 * Inserts a test service into the database and returns the generated ID.
 * @throws Exception
 */
function insertTestService(): int
{
    $serviceId = FService::storeService(getTestServiceData());
    if ($serviceId === null) {
        throw new Exception('Error inserting service.');
    }

    return $serviceId;
}

/**
 * Tests creating and managing a service.
 */
function testServiceManagement(): void
{
    echo "Starting Service Management Test...\n";

    try {
        $locationId = insertTestLocation();
        echo "ID della location inserita: $locationId\n";

        $location = FLocation::load($locationId);
        if ($location) {
            echo "Location caricata:\n";
            echo "ID: " . $location->getIdLocation() . "\n";
            echo "Nome: " . $location->getName() . "\n";
        } else {
            echo "Errore: Impossibile caricare la location con ID $locationId.\n";
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . "\n";
    }

    try {
        $serviceId = insertTestService();
        echo "Service created with ID: $serviceId\n";

        $loadedService = FService::loadService($serviceId);
        if ($loadedService) {
            echo "Loaded Service: \n";
            echo "ID: " . $loadedService->getIdService() . "\n";
            echo "Name: " . $loadedService->getName() . "\n";
        } else {
            echo "Failed to load service.\n";
        }

        echo "Updating service name to 'Updated Test Service'...\n";
        $loadedService->setName("Updated Test Service");
        $updateResult = FService::updateService($loadedService);
        if ($updateResult) {
            echo "Service updated successfully.\n";
        } else {
            echo "Failed to update service.\n";
        }

        echo "Adding location to service...\n";
        insertTestLocation($serviceId);

        echo "Loading locations for service...\n";
        $locations = FService::loadLocations($serviceId);
        if (!empty($locations)) {
            echo "Loaded Locations:\n";
            foreach ($locations as $location) {
                echo "- " . $location->getName() . "\n";
            }
        } else {
            echo "No locations found.\n";
        }

        echo "Deleting service...\n";
        $deleteResult = FService::deleteService($serviceId);
        if ($deleteResult) {
            echo "Service deleted successfully.\n";
        } else {
            echo "Failed to delete service.\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }

    echo "Testing loadAllServices...\n";
    $allServices = FService::loadAllServices();
    if (!empty($allServices)) {
        echo "All Services:\n";
        foreach ($allServices as $service) {
            echo "- ID: " . $service->getIdService() . ", Name: " . $service->getName() . "\n";
        }
    } else {
        echo "No services found.\n";
    }

    echo "Removing location from service...\n";
    $firstLocation = $locations[0]; // Assume at least one location exists
    $removeResult = FService::removeLocationFromService($serviceId, $firstLocation->getIdLocation());
    if ($removeResult) {
        echo "Location removed successfully.\n";
    } else {
        echo "Failed to remove location.\n";
    }

    echo "Testing existsService...\n";
    if (FService::existsService($serviceId)) {
        echo "Service exists in the database.\n";
    } else {
        echo "Service does not exist in the database.\n";
    }

    echo "Testing existsLocationInService...\n";
    if (FService::existsLocationInService($serviceId, $locations[0]->getIdLocation())) {
        echo "Location is associated with the service.\n";
    } else {
        echo "Location is not associated with the service.\n";
    }


    echo "Service Management Test Completed.\n";
}

// Run tests
testServiceManagement();
