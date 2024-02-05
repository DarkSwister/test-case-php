<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client[]    findAll()
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function add(Client $client): void
    {
        $this->_em->persist($client);
        $this->_em->flush();
    }

    public function update(Client $client): void
    {
        $this->_em->persist($client);
        $this->_em->flush();
    }
}
