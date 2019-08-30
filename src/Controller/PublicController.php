<?php

namespace App\Controller;

use App\Repository\DriverRepository;
use App\Repository\RentalRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(DriverRepository $driverRepository, VehicleRepository $vehicleRepository, RentalRepository $rentalRepository)
    {
        $drivers = $driverRepository->findAll();
        $vehicles = $vehicleRepository->findAll();
        $rentals = $rentalRepository->findAll();
        $vehicleNotRenteds = [];
        $rentedVehicles = [];
        foreach ($rentals as $rental) {
            $rentedVehicles[] = $rental->getVehicle();
        }

        foreach ($vehicles as $vehicle) {
            foreach ($rentedVehicles as $rentedVehicle) {
                if ($vehicle->getImmatriculation()!= $rentedVehicle->getImmatriculation()) {
                    $vehicleNotRenteds[] = $vehicle;
                }
            }
        }
        $vehicleNotRented = count($vehicleNotRenteds);
        return $this->render('public/home.html.twig', [
            'drivers' => $drivers,
            'vehicles' => $vehicles,
            'rentals' => $rentals,
            'vehicleNotRented' => $vehicleNotRented
        ]);
    }
}
