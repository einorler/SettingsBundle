<?php

namespace ONGR\SettingsBundle\Tests\Unit\Filter;

use ONGR\ElasticsearchDSL\Search;
use ONGR\SettingsBundle\Filter\SkipHiddenSettingsFilter;

class SkipHiddenSettingsFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests if the filter modifies search correctly
     */
    public function testModifySearch()
    {
        $search = new Search();
        $filter = new SkipHiddenSettingsFilter();

        $filter->modifySearch($search);

        $this->assertEquals(
            [
                'query' => [
                    'bool' => [
                        'must_not' => [
                            [
                                'term' => [
                                    'type' => 'hidden',
                                ],
                            ],
                            [
                                'term' => [
                                    'type' => 'experiment',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $search->toArray()
        );
    }
}
