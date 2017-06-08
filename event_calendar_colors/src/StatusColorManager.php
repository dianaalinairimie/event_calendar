<?php

namespace Drupal\event_calendar_colors;

use Drupal\Core\Asset\LibraryDiscoveryInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\pathauto\AliasCleanerInterface;

/**
 * Class StatusColorManager.
 *
 * @package Drupal\event_calendar_colors\Form
 */
class StatusColorManager implements StatusColorManagerInterface {

  /**
   * The theme handler service.
   *
   * @var Drupal\Core\Extension\ThemeHandler
   */
  protected $themeHandler;

  /**
   * The library discovery service.
   *
   * @var Drupal\Core\Asset\LibraryDiscovery
   */
  protected $libraryDiscovery;

  /**
   * The alias cleaner service.
   *
   * @var Drupal\Core\Asset\LibraryDiscovery
   */
  protected $aliasCleaner;

  /**
   * The file system service.
   *
   * @var Drupal\Core\File\FileSystem
   */
  protected $fileSystem;

  /**
   * Constructs an ColorColorManager object.
   *
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   The theme handler.
   * @param \Drupal\Core\Asset\LibraryDiscoveryInterface $library_discovery
   *   The library discovery.
   * @param \Drupal\pathauto\AliasCleanerInterface $alias_cleaner
   *   The alias cleaner.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system.
   */
  public function __construct(ThemeHandlerInterface $theme_handler, LibraryDiscoveryInterface $library_discovery, AliasCleanerInterface $alias_cleaner, FileSystemInterface $file_system) {
    $this->themeHandler = $theme_handler;
    $this->libraryDiscovery = $library_discovery;
    $this->aliasCleaner = $alias_cleaner;
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public function generateCssFiles($event_colors) {
    // Search for the library.
    $libs = $this->libraryDiscovery->getLibraryByName('event_calendar_colors', 'event_color_default');
    $css_files = $libs['css'];
    // Set return value to TRUE to check if the css files are written correctly.
    $return = TRUE;
    // Parse each css file and replace the color and class placeholders.
    foreach ($css_files as $css) {
      $content = '';
      $css_content = file_get_contents(getcwd() . '/' . $css['data']);

      foreach ($event_colors as $status_class => $status_color) {
        $temp_content = str_replace(EVENT_STATUS_CSS_PLACEHOLDER, $this->aliasCleaner->cleanString($status_class), $css_content);
        $temp_content = str_replace(EVENT_STATUS_DEFAULT_COLOR, $status_color, $temp_content);
        $content .= $temp_content;
      }

      // Generate the css file names.
      $file_name = strrchr($css['data'], '/');
      $path = implode('/', [
        $this->fileSystem->realpath(file_default_scheme() . "://"),
        'css' . $file_name,
      ]);
      // Check if the file was written.
      $return = $return && file_put_contents($path, $content);
    }

    return $return;
  }

}
