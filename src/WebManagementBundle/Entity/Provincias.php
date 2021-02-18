<?php

namespace WebManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Provincias
 *
 * @ORM\Table(name="provincias")
 * @ORM\Entity(repositoryClass="WebManagementBundle\Repository\ProvinciasRepository")
 */
class Provincias
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_provincia", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProvincia;

    /**
     * @var string
     *
     * @ORM\Column(name="provincia", type="string", length=30, nullable=true)
     */
    private $provincia = 'NULL';

    /**
     * Get idProvincia
     *
     * @return integer
     */
    public function getIdProvincia()
    {
        return $this->idProvincia;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     *
     * @return Provincias
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return string
     */
    public function getProvincia()
    {
        return $this->provincia;
    }
}
