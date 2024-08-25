<?php

namespace App\Factory;

use App\Entity\Poll;
use Doctrine\ORM\EntityManager;

class PollFactory
{
    /**
     * Creates a Manager Poll object.
     *
     * @return Poll The newly created Poll object.
     */
    public static function createManagerPoll()
    {
        return self::createPoll(Poll::MANAGER);
    }

    /**
     * Creates a Salary Poll object.
     *
     * @return Poll The newly created Poll object.
     */
    public static function createSalaryPoll()
    {
        return self::createPoll(Poll::SALARY);
    }

    /**
     * Creates a new Poll object.
     *
     * @param mixed $type The type of the poll. This can be a string or any other type of value.
     *
     * @return Poll The newly created Poll object.
     */
    public static  function createPoll($type)
    {
        $poll = new Poll();
        $poll->setType($type);
        $poll->addAnswers(AnswerFactory::createAllAnswers());

        return $poll;
    }
}
