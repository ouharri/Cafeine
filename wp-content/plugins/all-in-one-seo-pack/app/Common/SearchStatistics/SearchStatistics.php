<?php
namespace AIOSEO\Plugin\Common\SearchStatistics;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that holds our Search Statistics feature.
 *
 * @since 4.3.0
 */
class SearchStatistics {
	/**
	 * Returns the data for Vue.
	 *
	 * @since 4.3.0
	 *
	 * @return array The data for Vue.
	 */
	public function getVueData() {
		$data = [
			'isConnected'         => false,
			'latestAvailableDate' => null,
			'range'               => [],
			'rolling'             => aioseo()->internalOptions->internal->searchStatistics->rolling,
			'authedSite'          => null,
			'data'                => [
				'seoStatistics' => $this->getSeoOverviewData(),
				'keywords'      => $this->getKeywordsData()
			]
		];

		return $data;
	}

	/**
	 * Returns the data for the SEO Overview.
	 *
	 * @since 4.3.0
	 *
	 * @param  string $dateRange The date range.
	 * @return array             The data for the SEO Overview.
	 */
	protected function getSeoOverviewData( $dateRange = [] ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$pageRows = [
			'/'                       => [
				'ctr'         => '1.25',
				'page'        => '/',
				'clicks'      => 15460,
				'position'    => '74.01',
				'difference'  => [
					'ctr'         => '-0.23',
					'decay'       => 192211,
					'clicks'      => -26,
					'current'     => true,
					'position'    => '19.66',
					'comparison'  => true,
					'impressions' => 192237
				],
				'impressions' => 1235435,
				'context'     => [],
				'postId'      => 0,
				'postTitle'   => '/',
				'seoScore'    => 65
			],
			'/test-page/'             => [
				'ctr'         => '0.30',
				'page'        => '/test-page/',
				'clicks'      => 5688,
				'position'    => '35.28',
				'difference'  => [
					'ctr'         => '0.05',
					'decay'       => 378973,
					'clicks'      => 1941,
					'current'     => true,
					'position'    => '-2.33',
					'comparison'  => true,
					'impressions' => 377032
				],
				'impressions' => 1881338,
				'context'     => [],
				'postId'      => 0,
				'postTitle'   => '/test-page/',
				'seoScore'    => 95
			],
			'/high-ranking-page/'     => [
				'ctr'         => '6.03',
				'page'        => '/high-ranking-page/',
				'clicks'      => 3452,
				'position'    => '22.85',
				'difference'  => [
					'ctr'         => '-0.95',
					'decay'       => -5986,
					'clicks'      => -898,
					'current'     => true,
					'position'    => '-0.22',
					'comparison'  => true,
					'impressions' => -5088
				],
				'impressions' => 57248,
				'context'     => [],
				'postId'      => 0,
				'postTitle'   => '/high-ranking-page/',
				'seoScore'    => 100
			],
			'/pricing/'               => [
				'ctr'         => '1.35',
				'page'        => '/pricing/',
				'clicks'      => 2749,
				'position'    => '40.40',
				'difference'  => [
					'ctr'         => '-0.16',
					'decay'       => 15991,
					'clicks'      => -94,
					'current'     => true,
					'position'    => '9.77',
					'comparison'  => true,
					'impressions' => 16085
				],
				'impressions' => 203794,
				'context'     => [],
				'postId'      => 0,
				'postTitle'   => '/pricing/',
				'seoScore'    => 100
			],
			'/features-and-benefits/' => [
				'ctr'         => '2.48',
				'page'        => '/features-and-benefits/',
				'clicks'      => 2600,
				'position'    => '15.53',
				'difference'  => [
					'ctr'         => '0.99',
					'decay'       => 23466,
					'clicks'      => 1367,
					'current'     => true,
					'position'    => '1.51',
					'comparison'  => true,
					'impressions' => 22099
				],
				'impressions' => 104769,
				'context'     => [],
				'postId'      => 0,
				'postTitle'   => '/features-and-benefits/',
				'seoScore'    => 90
			],
			'/documentation/'         => [
				'ctr'         => '2.64',
				'page'        => '/documentation/',
				'clicks'      => 1716,
				'position'    => '27.85',
				'difference'  => [
					'ctr'         => '-0.04',
					'decay'       => 7274,
					'clicks'      => 167,
					'current'     => true,
					'position'    => '-9.51',
					'comparison'  => true,
					'impressions' => 7107
				],
				'impressions' => 64883,
				'context'     => [],
				'postId'      => 0,
				'postTitle'   => '/documentation/',
				'seoScore'    => 93
			],
			'/blog/'                  => [
				'ctr'         => '3.75',
				'page'        => '/blog/',
				'clicks'      => 1661,
				'position'    => '36.60',
				'difference'  => [
					'ctr'         => '0.42',
					'decay'       => -3145,
					'clicks'      => 77,
					'current'     => true,
					'position'    => '-9.40',
					'comparison'  => true,
					'impressions' => -3222
				],
				'impressions' => 44296,
				'context'     => [],
				'postId'      => 0,
				'postTitle'   => '/blog/',
				'seoScore'    => 97
			],
			'/blog/my-best-content/'  => [
				'ctr'         => '7.08',
				'page'        => '/blog/my-best-content/',
				'clicks'      => 1573,
				'position'    => '9.61',
				'difference'  => [
					'ctr'         => '0.16',
					'decay'       => -201,
					'clicks'      => 22,
					'current'     => true,
					'position'    => '-2.03',
					'comparison'  => true,
					'impressions' => -223
				],
				'impressions' => 22203,
				'context'     => [],
				'postId'      => 0,
				'postTitle'   => '/blog/my-best-content/',
				'seoScore'    => 56
			],
			'/contact-us/'            => [
				'ctr'         => '1.45',
				'page'        => '/contact-us/',
				'clicks'      => 1550,
				'position'    => '32.05',
				'difference'  => [
					'ctr'         => '0.12',
					'decay'       => 1079,
					'clicks'      => 140,
					'current'     => true,
					'position'    => '-3.47',
					'comparison'  => true,
					'impressions' => 939
				],
				'impressions' => 106921,
				'context'     => [],
				'postId'      => 0,
				'postTitle'   => '/contact-us/',
				'seoScore'    => 78
			],
			'/support/'               => [
				'ctr'         => '5.94',
				'page'        => '/support/',
				'clicks'      => 1549,
				'position'    => '25.83',
				'difference'  => [
					'ctr'         => '-0.74',
					'decay'       => 3885,
					'clicks'      => 62,
					'current'     => true,
					'position'    => '-1.48',
					'comparison'  => true,
					'impressions' => 3823
				],
				'impressions' => 26099,
				'context'     => [],
				'postId'      => 0,
				'postTitle'   => '/support/',
				'seoScore'    => 86
			]
		];

		return [
			'statistics' => [
				'ctr'         => '0.74',
				'clicks'      => 111521,
				'keywords'    => 19335,
				'position'    => '49.28',
				'difference'  => [
					'ctr'         => '0.03',
					'clicks'      => 1736,
					'keywords'    => 2853,
					'position'    => '1.01',
					'impressions' => -475679
				],
				'impressions' => 14978074
			],
			'intervals'  => [
				[
					'ctr'         => '0.72',
					'date'        => '2022-10-23',
					'clicks'      => 7091,
					'position'    => '48.88',
					'impressions' => 985061
				],
				[
					'ctr'         => '0.77',
					'date'        => '2022-10-30',
					'clicks'      => 8544,
					'position'    => '46.48',
					'impressions' => 1111602
				],
				[
					'ctr'         => '0.73',
					'date'        => '2022-11-06',
					'clicks'      => 9087,
					'position'    => '48.44',
					'impressions' => 1247506
				],
				[
					'ctr'         => '0.75',
					'date'        => '2022-11-13',
					'clicks'      => 9952,
					'position'    => '50.03',
					'impressions' => 1326910
				],
				[
					'ctr'         => '0.73',
					'date'        => '2022-11-20',
					'clicks'      => 9696,
					'position'    => '50.28',
					'impressions' => 1324633
				],
				[
					'ctr'         => '0.69',
					'date'        => '2022-11-27',
					'clicks'      => 9590,
					'position'    => '51.03',
					'impressions' => 1382602
				],
				[
					'ctr'         => '0.71',
					'date'        => '2022-12-04',
					'clicks'      => 9691,
					'position'    => '51.07',
					'impressions' => 1365509
				],
				[
					'ctr'         => '0.71',
					'date'        => '2022-12-11',
					'clicks'      => 9291,
					'position'    => '51.22',
					'impressions' => 1316184
				],
				[
					'ctr'         => '0.80',
					'date'        => '2022-12-18',
					'clicks'      => 8659,
					'position'    => '48.20',
					'impressions' => 1081944
				],
				[
					'ctr'         => '0.75',
					'date'        => '2022-12-25',
					'clicks'      => 6449,
					'position'    => '49.31',
					'impressions' => 857591
				],
				[
					'ctr'         => '0.66',
					'date'        => '2023-01-01',
					'clicks'      => 5822,
					'position'    => '48.16',
					'impressions' => 876828
				],
				[
					'ctr'         => '0.77',
					'date'        => '2023-01-08',
					'clicks'      => 7501,
					'position'    => '47.34',
					'impressions' => 975764
				],
				[
					'ctr'         => '0.90',
					'date'        => '2023-01-16',
					'clicks'      => 10148,
					'position'    => '48.29',
					'impressions' => 1125940
				]
			],
			'pages'      => [
				'topPages'   => [
					'rows' => $pageRows
				],
				'paginated'  => [
					'rows'    => $pageRows,
					'totals'  => [
						'page'  => 1,
						'pages' => 292,
						'total' => 2914
					],
					'filters' => [
						[
							'slug'   => 'all',
							'name'   => 'All',
							'active' => true
						],
						[
							'slug'   => 'topLosing',
							'name'   => 'Top Losing',
							'active' => false
						],
						[
							'slug'   => 'topWinning',
							'name'   => 'Top Winning',
							'active' => false
						]
					]
				],
				'topLosing'  => [
					'rows' => []
				],
				'topWinning' => [
					'rows' => []
				]
			]
		];
	}

	/**
	 * Returns the data for the Keywords.
	 *
	 * @since 4.3.0
	 *
	 * @param  string $dateRange The date range.
	 * @return array             The data for the Keywords.
	 */
	protected function getKeywordsData( $dateRange = [] ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$keywordsRows = [
			[
				'ctr'         => '4.89',
				'clicks'      => 5000,
				'keyword'     => 'best seo plugin',
				'position'    => '1.93',
				'difference'  => [
					'ctr'         => '-1.06',
					'decay'       => 6590,
					'clicks'      => -652,
					'position'    => '0.07',
					'impressions' => 7242
				],
				'impressions' => 102233
			],
			[
				'ctr'         => '7.06',
				'clicks'      => 4404,
				'keyword'     => 'aioseo is the best',
				'position'    => '1.32',
				'difference'  => [
					'ctr'         => '0.13',
					'decay'       => 8586,
					'clicks'      => 633,
					'position'    => '0.12',
					'impressions' => 7953
				],
				'impressions' => 62357
			],
			[
				'ctr'         => '2.81',
				'clicks'      => 1715,
				'keyword'     => 'analyze my seo',
				'position'    => '6.29',
				'difference'  => [
					'ctr'         => '-0.03',
					'decay'       => 13217,
					'clicks'      => 347,
					'position'    => '-0.34',
					'impressions' => 12870
				],
				'impressions' => 61102
			],
			[
				'ctr'         => '7.46',
				'clicks'      => 717,
				'keyword'     => 'wordpress seo',
				'position'    => '1.18',
				'difference'  => [
					'ctr'         => '-0.69',
					'decay'       => 2729,
					'clicks'      => 144,
					'position'    => '-0.08',
					'impressions' => 2585
				],
				'impressions' => 9614
			],
			[
				'ctr'         => '6.66',
				'clicks'      => 446,
				'keyword'     => 'best seo plugin pro',
				'position'    => '1.65',
				'difference'  => [
					'ctr'         => '0.36',
					'decay'       => -121,
					'clicks'      => 16,
					'position'    => '0.33',
					'impressions' => -137
				],
				'impressions' => 6693
			],
			[
				'ctr'         => '7.39',
				'clicks'      => 409,
				'keyword'     => 'aioseo wordpress',
				'position'    => '1.77',
				'difference'  => [
					'ctr'         => '-0.39',
					'decay'       => 534,
					'clicks'      => 19,
					'position'    => '-0.13',
					'impressions' => 515
				],
				'impressions' => 5531
			],
			[
				'ctr'         => '1.11',
				'clicks'      => 379,
				'keyword'     => 'headline analyzer aioseo',
				'position'    => '8.41',
				'difference'  => [
					'ctr'         => '0.43',
					'decay'       => 134,
					'clicks'      => 147,
					'position'    => '-1.36',
					'impressions' => -13
				],
				'impressions' => 34043
			],
			[
				'ctr'         => '2.63',
				'clicks'      => 364,
				'keyword'     => 'best seo plugin plugin',
				'position'    => '2.38',
				'difference'  => [
					'ctr'         => '0.06',
					'decay'       => 836,
					'clicks'      => 29,
					'position'    => '0.20',
					'impressions' => 807
				],
				'impressions' => 13837
			],
			[
				'ctr'         => '1.52',
				'clicks'      => 326,
				'keyword'     => 'best seo plugin pack',
				'position'    => '4.14',
				'difference'  => [
					'ctr'         => '-0.19',
					'decay'       => -1590,
					'clicks'      => -66,
					'position'    => '0.64',
					'impressions' => -1524
				],
				'impressions' => 21450
			],
			[
				'ctr'         => '6.70',
				'clicks'      => 264,
				'keyword'     => 'youtube title analyzer aioseo',
				'position'    => '7.19',
				'difference'  => [
					'ctr'         => '4.73',
					'decay'       => 3842,
					'clicks'      => 257,
					'position'    => '-4.18',
					'impressions' => 3585
				],
				'impressions' => 3940
			]
		];

		return [
			'paginated'             => [
				'rows'    => $keywordsRows,
				'totals'  => [
					'page'  => 1,
					'pages' => 2500,
					'total' => 25000
				],
				'filters' => [
					[
						'slug'   => 'all',
						'name'   => 'All',
						'active' => true
					],
					[
						'slug'   => 'topLosing',
						'name'   => 'Top Losing',
						'active' => false
					],
					[
						'slug'   => 'topWinning',
						'name'   => 'Top Winning',
						'active' => false
					]
				]
			],
			'topLosing'             => [],
			'topWinning'            => [],
			'topKeywords'           => $keywordsRows,
			'distribution'          => [
				'top3'       => '6.86',
				'top10'      => '11.03',
				'top50'      => '52.10',
				'top100'     => '30.01',
				'difference' => [
					'top3'   => '24.31',
					'top10'  => '33.70',
					'top50'  => '-30.50',
					'top100' => '-27.51'
				]
			],
			'distributionIntervals' => [
				[
					'date'   => '2022-10-23',
					'top3'   => '30.70',
					'top10'  => '38.60',
					'top50'  => '24.50',
					'top100' => '6.20'
				],
				[
					'date'   => '2022-10-30',
					'top3'   => '31.60',
					'top10'  => '42.10',
					'top50'  => '21.00',
					'top100' => '5.30'
				],
				[
					'date'   => '2022-11-06',
					'top3'   => '31.30',
					'top10'  => '44.40',
					'top50'  => '20.30',
					'top100' => '4.00'
				],
				[
					'date'   => '2022-11-13',
					'top3'   => '31.70',
					'top10'  => '44.20',
					'top50'  => '20.20',
					'top100' => '3.90'
				],
				[
					'date'   => '2022-11-20',
					'top3'   => '31.70',
					'top10'  => '45.70',
					'top50'  => '18.00',
					'top100' => '4.60'
				],
				[
					'date'   => '2022-11-27',
					'top3'   => '32.50',
					'top10'  => '47.80',
					'top50'  => '16.80',
					'top100' => '2.90'
				],
				[
					'date'   => '2022-12-04',
					'top3'   => '32.50',
					'top10'  => '47.20',
					'top50'  => '17.90',
					'top100' => '2.40'
				],
				[
					'date'   => '2022-12-11',
					'top3'   => '31.80',
					'top10'  => '43.70',
					'top50'  => '21.00',
					'top100' => '3.50'
				],
				[
					'date'   => '2022-12-18',
					'top3'   => '30.40',
					'top10'  => '43.60',
					'top50'  => '22.40',
					'top100' => '3.60'
				],
				[
					'date'   => '2022-12-25',
					'top3'   => '26.90',
					'top10'  => '37.20',
					'top50'  => '29.70',
					'top100' => '6.20'
				],
				[
					'date'   => '2023-01-01',
					'top3'   => '27.00',
					'top10'  => '33.80',
					'top50'  => '31.60',
					'top100' => '7.60'
				],
				[
					'date'   => '2023-01-08',
					'top3'   => '26.60',
					'top10'  => '38.60',
					'top50'  => '30.00',
					'top100' => '4.80'
				],
				[
					'date'   => '2023-01-16',
					'top3'   => '31.10',
					'top10'  => '43.90',
					'top50'  => '22.50',
					'top100' => '2.50'
				]
			]
		];
	}
}