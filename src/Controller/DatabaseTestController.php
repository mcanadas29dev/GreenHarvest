<?php
// src/Controller/DatabaseTestController.php
namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DatabaseTestController extends AbstractController
{
    #[Route('/db-test', name: 'db_test')]
    public function test(Connection $connection): Response
    {
        try {
            // Ejecutamos una consulta simple para probar la conexión
            $connection->executeQuery('SELECT 1');

            return new Response('<h1>OK: Conexión a la base de datos exitosa mcanadas29 </h1>');
        } catch (\Exception $e) {
            return new Response('<h1>NO OK: Error al conectar con la base de datos</h1><p>'.$e->getMessage().'</p>');
        }
    }
}
