<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\SettingsBundle\Service;

use Doctrine\Common\Cache\CacheProvider;
use ONGR\CookiesBundle\Cookie\Model\GenericCookie;
use ONGR\ElasticsearchBundle\Result\Aggregation\AggregationValue;
use ONGR\ElasticsearchBundle\Result\Result;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\TermsAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\TopHitsAggregation;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use ONGR\ElasticsearchDSL\Search;
use ONGR\SettingsBundle\Document\Experiment;
use ONGR\SettingsBundle\Event\Events;
use ONGR\SettingsBundle\Event\SettingActionEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use ONGR\ElasticsearchBundle\Service\Repository;
use ONGR\ElasticsearchBundle\Service\Manager;
use ONGR\SettingsBundle\Document\Setting;

/**
 * Class ExperimentsManager responsible for managing experiments actions.
 */
class ExperimentsManager
{
    const ALL_EXPERIMENTS_CACHE = 'ongr_settings_experiments';

    /**
     * Symfony event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Elasticsearch manager which handles setting repository.
     *
     * @var Manager
     */
    private $manager;

    /**
     * Settings repository.
     *
     * @var Repository
     */
    private $repo;

    /**
     * Cache pool container.
     *
     * @var CacheProvider
     */
    private $cache;

    /**
     * @param Repository               $repo
     * @param EventDispatcherInterface $eventDispatcher
     * @param CacheProvider            $cache
     */
    public function __construct(
        $repo,
        EventDispatcherInterface $eventDispatcher,
        CacheProvider $cache
    ) {
        $this->repo = $repo;
        $this->manager = $repo->getManager();
        $this->eventDispatcher = $eventDispatcher;
        $this->cache = $cache;
    }

    /**
     * @return array
     */
    public function getAllExperimentsArray()
    {
        $search = new Search();
        $search->addQuery(new MatchAllQuery());

        if ($this->cache->contains(self::ALL_EXPERIMENTS_CACHE)) {
            return $this->cache->fetch(self::ALL_EXPERIMENTS_CACHE);
        }

        // TODO: Change the execute method to an array formation
        $experiments = $this->repo->execute($search, Result::RESULTS_ARRAY);

        if (!empty($experiments)) {
            $this->cache->save(self::ALL_EXPERIMENTS_CACHE, $experiments);
        }

        return $experiments;
    }

    /**
     * Creates experiment.
     *
     * @param array $data
     *
     * @return Experiment
     */
    public function create(array $data = [])
    {
        $data = array_filter($data);
        if (!isset($data['name']) || !isset($data['profile'])) {
            throw new \LogicException('Missing one of the mandatory fields!');
        }

        $name = $data['name'];
        $existingExperiment = $this->get($name);

        if ($existingExperiment) {
            throw new \LogicException(sprintf('Setting %s already exists.', $name));
        }

        $settingClass = $this->repo->getClassName();
        /** @var Setting $setting */
        $experiment = new $settingClass();

        #TODO Introduce array populate function in Experiment document instead of this foreach.
        foreach ($data as $key => $value) {
            $experiment->{'set'.ucfirst($key)}($value);
        }

        $this->manager->persist($experiment);
        $this->manager->commit();

        $this->cache->delete(self::ALL_EXPERIMENTS_CACHE);

        return $experiment;
    }

    /**
     * Returns setting object.
     *
     * @param string $name
     *
     * @return Experiment
     */
    public function get($name)
    {
        /** @var Experiment $experiment */
        $experiment = $this->repo->findOneBy(['name.name' => $name]);

        return $experiment;
    }
}
