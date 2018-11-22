<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Respuesta
 *
 * @ORM\Table(name="respuesta")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RespuestaRepository")
 */
class Respuesta
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="respuesta", type="boolean", nullable=false)
     */
    private $respuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */
    private $comentarios;

    /**
     * One Respuesta has One Academico.
     * @ORM\OneToOne(targetEntity="Academico", inversedBy="respuesta")
     * @ORM\JoinColumn(name="academico_id", referencedColumnName="id")
     */
    private $academico;

    /**
     * @return mixed
     */
    public function getAcademico()
    {
        return $this->academico;
    }

    /**
     * @param mixed $academico
     */
    public function setAcademico($academico)
    {
        $this->academico = $academico;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set respuesta
     *
     * @param boolean $respuesta
     *
     * @return Respuesta
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get respuesta
     *
     * @return bool
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return Respuesta
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    public function __toString() {
        return $this->getAcademico()->getSlug();
    }
}

