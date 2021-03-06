<?php

declare(strict_types=1);

/*
 * This file is a part of Sculpin.
 *
 * (c) Dragonfly Development Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sculpin\Core\Source;

/**
 * Composite Data Source.
 *
 * @author Beau Simensen <beau@dflydev.com>
 */
class CompositeDataSource implements DataSourceInterface
{
    /**
     * Data sources
     *
     * @var array
     */
    private $dataSources = [];

    /**
     * Constructor.
     *
     * @param array $dataSources Data sources
     */
    public function __construct(array $dataSources = [])
    {
        foreach ($dataSources as $dataSource) {
            $this->dataSources[$dataSource->dataSourceId()] = $dataSource;
        }
    }

    /**
     * Add a Data Source.
     *
     * @param DataSourceInterface $dataSource Data Source
     */
    public function addDataSource(DataSourceInterface $dataSource): void
    {
        $this->dataSources[$dataSource->dataSourceId()] = $dataSource;
    }

    /**
     * Backing Data Sources
     *
     * @return array
     */
    public function dataSources(): array
    {
        return $this->dataSources;
    }

    /**
     * {@inheritdoc}
     */
    public function dataSourceId(): string
    {
        return 'CompositeDataSource('.implode(',', array_map(function ($dataSource) {
            return $dataSource->dataSourceId();
        }, $this->dataSources));
    }

    /**
     * {@inheritdoc}
     */
    public function refresh(SourceSet $sourceSet): void
    {
        foreach ($this->dataSources as $dataSource) {
            $dataSource->refresh($sourceSet);
        }
    }
}
