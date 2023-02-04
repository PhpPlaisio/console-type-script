<?php
declare(strict_types=1);

namespace Plaisio\Console\TypeScript\Command;

use Plaisio\Console\Assets\Helper\PlaisioXmlQueryHelper;
use Plaisio\Console\Command\PlaisioCommand;
use Plaisio\Console\Exception\ConfigException;
use Plaisio\Console\Helper\PlaisioXmlPathHelper;
use Plaisio\Console\TypeScript\Helper\FixHelper;
use SetBased\Helper\Cast;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Path;

/**
 * Command for fixing from TypeScript generated JavaScript files as a proper AMD module according to Plaisio standards.
 */
class FixerCommand extends PlaisioCommand
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The file extension of JavaScript files.
   *
   * @var string
   */
  private string $jsExtension = 'js';

  /**
   * The path to the JavScript asset directory.
   *
   * @var string
   */
  private string $jsPath;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function configure()
  {
    $this->setName('plaisio:type-script-fixer')
         ->setDescription('Fixes from TypeScript generated JavaScript files as a proper AMD module according to Plaisio standards')
         ->addArgument('path', InputArgument::REQUIRED, 'The path to a JavaScript file or directory for recursive traversal');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @throws ConfigException
   */
  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $this->io->title('Plaisio: TypeScript Fixer');

    $this->readResourceDir();
    $helper = new FixHelper($this->io, $this->jsPath);

    $path = Cast::toManString($input->getArgument('path'));
    if (is_file($path) && Path::hasExtension($path, $this->jsExtension))
    {
      $helper->fixJavaScriptFile($path);
    }
    elseif (is_dir($path))
    {
      $helper->fixJavaScriptFiles($path);
    }
    else
    {
      $this->io->error(sprintf("Path '%s' is not JavaScript file nor a directory", $path));
    }

    return 0;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Reads the asset root directory (a.k.a. the resource directory).
   */
  private function readResourceDir(): void
  {
    $path         = PlaisioXmlPathHelper::plaisioXmlPath('assets');
    $helper       = new PlaisioXmlQueryHelper($path);
    $this->jsPath = $helper->queryAssetDir('js');
    if (!file_exists($this->jsPath))
    {
      throw new ConfigException("JavaScript asset directory '%s' does not exists", $this->jsPath);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
