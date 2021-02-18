<?php

namespace WebManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comunidades
 *
 * @ORM\Table(name="comunidades")
 * @ORM\Entity
 */
class Comunidades
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="id_comunidad", type="boolean", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idComunidad;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;


}

