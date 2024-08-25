<?php

namespace App\Factory;

use App\Entity\Survey;
use App\Entity\User;
use App\Repository\SurveyRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Survey>
 *
 * @method        Survey|Proxy create(array|callable $attributes = [])
 * @method static Survey|Proxy createOne(array $attributes = [])
 * @method static Survey|Proxy find(object|array|mixed $criteria)
 * @method static Survey|Proxy findOrCreate(array $attributes)
 * @method static Survey|Proxy first(string $sortedField = 'id')
 * @method static Survey|Proxy last(string $sortedField = 'id')
 * @method static Survey|Proxy random(array $attributes = [])
 * @method static Survey|Proxy randomOrCreate(array $attributes = [])
 * @method static SurveyRepository|RepositoryProxy repository()
 * @method static Survey[]|Proxy[] all()
 * @method static Survey[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Survey[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Survey[]|Proxy[] findBy(array $attributes)
 * @method static Survey[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Survey[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class SurveyFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'count' => self::faker()->randomNumber(),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'status' => self::faker()->text(255),
            'title' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Survey $survey): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Survey::class;
    }

    /**
     * Creates a new survey from the dashboard modal.
     *
     * @param string $title The title of the survey.
     * @param int $count The count of the survey.
     * @param User $user The user who is creating the survey.
     *
     * @return Survey The newly created survey object.
     */
    public static function createSurveyFromDashboardModal($title, $count, $user) {
        $survey = new Survey();
        $survey->setTitle($title);
        $survey->setCount($count);
        $survey->setCreator($user);
        return $survey;
    }
}
