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
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\TermsAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\TopHitsAggregation;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;
use ONGR\ElasticsearchDSL\Query\TermQuery;
use ONGR\ElasticsearchDSL\Search;
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
    const EXPERIMENTS_CACHE_NAME = 'ongr_settings_experiments';

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

        if ($this->cache->contains(self::EXPERIMENTS_CACHE_NAME)) {
            return $this->cache->fetch(self::EXPERIMENTS_CACHE_NAME);
        }

        $experiments = $this->repo->findArray($search);
        $this->cache->save(self::EXPERIMENTS_CACHE_NAME, $experiments);

        return $experiments;
    }
}
