<?php
namespace Elementor\Core\App\Modules\ImportExport\Directories;

use Elementor\Core\App\Modules\ImportExport\Iterator;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Type extends Base {

	private $post_type;

	private $show_page_on_front;

	private $page_on_front_id;

	protected function get_name() {
		return $this->post_type;
	}

	public function __construct( Iterator $iterator, Base $parent, $post_type ) {
		parent::__construct( $iterator, $parent );

		$this->post_type = $post_type;

		if ( 'page' === $post_type ) {
			$this->init_page_on_front_data();
		}
	}

	public function export() {
		$query_args = [
			'post_type' => $this->post_type,
			'post_status' => 'publish',
			'posts_per_page' => 20,
			'meta_query' => [
				[
					'key' => '_elementor_data',
					'compare' => 'EXISTS',
				],
				[
					'key' => '_elementor_data',
					'compare' => '!=',
					'value' => '[]',
				],
			],
		];

		$query = new \WP_Query( $query_args );

		$manifest_data = [];

		foreach ( $query->posts as $post ) {
			$document = Plugin::$instance->documents->get( $post->ID );

			$post_manifest_data = [
				'title' => $post->post_title,
				'doc_type' => $document->get_name(),
				'thumbnail' => get_the_post_thumbnail_url( $post ),
				'url' => get_permalink( $post ),
			];

			if ( $post->ID === $this->page_on_front_id ) {
				$post_manifest_data['show_on_front'] = true;
			}

			$manifest_data[ $post->ID ] = $post_manifest_data;

			$this->exporter->add_json_file( $post->ID, $document->get_export_data() );
		}

		return $manifest_data;
	}

	protected function import( array $import_settings ) {
		$result = [
			'success' => [],
			'failed' => [],
		];

		foreach ( $import_settings as $id => $post_settings ) {
			try {
				$import = $this->import_post( $id, $post_settings );

				if ( is_wp_error( $import ) ) {
					$result['failed'][ $id ] = $import->get_error_message();

					continue;
				}

				$result['success'][] = $import;
			} catch ( \Error $error ) {
				$result['failed'][ $id ] = $error->getMessage();
			}
		}

		return $result;
	}

	public function import_post( $id, array $post_settings ) {
		$post_data = $this->importer->read_json_file( $id );

		$new_document = Plugin::$instance->documents->create(
			$post_settings['doc_type'],
			[
				'post_title' => $post_settings['title'],
				'post_type' => $this->post_type,
				'post_status' => 'publish',
			]
		);

		if ( is_wp_error( $new_document ) ) {
			return $new_document;
		}

		$new_document->import( $post_data );

		if ( $post_settings['thumbnail'] ) {
			$attachment = Plugin::$instance->templates_manager->get_import_images_instance()->import( [ 'url' => $post_settings['thumbnail'] ] );

			set_post_thumbnail( $new_document->get_main_post(), $attachment['id'] );
		}

		$new_id = $new_document->get_main_id();

		if ( $this->show_page_on_front && ! empty( $post_settings['show_on_front'] ) ) {
			update_option( 'page_on_front', $new_id );
		}

		return $new_id;
	}

	private function init_page_on_front_data() {
		$this->show_page_on_front = 'page' === get_option( 'show_on_front' );

		if ( $this->show_page_on_front && $this->exporter ) {
			$this->page_on_front_id = (int) get_option( 'page_on_front' );
		}
	}
}
