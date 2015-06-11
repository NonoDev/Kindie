<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository{
    /**
     * @param $string
     * @return mixed
     */
    public function findByLetters($string){
        return $this->getEntityManager()->createQuery('SELECT u FROM AppBundle:Proyecto u
                WHERE u.nombre LIKE :string')
            ->setParameter('string','%'.$string.'%')
            ->getResult();
    }

    public function findByLeido(){
        return $this->getEntityManager()->createQuery('SELECT m FROM AppBundle:Mensaje m WHERE m.leido = FALSE ')
            ->getResult();
    }


}