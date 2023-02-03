<?php
declare(strict_types=1);

namespace Plaisio\Console\TypeScript\Test\Helper;

use PHPUnit\Framework\TestCase;
use Plaisio\Console\Application\PlaisioApplication;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Filesystem\Path;

/**
 * Test cases for class FixHelper.
 */
class FixHelperTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test *.main.js files are not modified.
   */
  public function testDontTouchMain(): void
  {
    copy(Path::join(__DIR__, __FUNCTION__, 'plaisio-assets.xml'), Path::join(getcwd(), 'plaisio-assets.xml'));

    $jsPath       = Path::makeRelative(Path::join(__DIR__,
                                                  __FUNCTION__,
                                                  'js/Plaisio/PageDecorator',
                                                  'CorePageDecorator.main.js'),
                                       getcwd());
    $orgPath      = Path::changeExtension($jsPath, 'org.js');
    $expectedPath = Path::changeExtension($jsPath, 'expected.js');
    copy($orgPath, $jsPath);

    $application = new PlaisioApplication();
    $application->setAutoExit(false);
    $tester = new ApplicationTester($application);
    $tester->run(['command' => 'plaisio:type-script-fixer',
                  'path'    => $jsPath]);

    $output = $tester->getDisplay();
    self::assertSame(0, $tester->getStatusCode(), $output);
    self::assertFileEquals($expectedPath, $jsPath);

    unlink($jsPath);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test fixing defining deps with global packages and local packages.
   */
  public function testFixDefineDeps(): void
  {
    copy(Path::join(__DIR__, __FUNCTION__, 'plaisio-assets.xml'), Path::join(getcwd(), 'plaisio-assets.xml'));

    $jsPath       = Path::makeRelative(Path::join(__DIR__, __FUNCTION__, 'js', 'Test', 'Foo.js'), getcwd());
    $orgPath      = Path::changeExtension($jsPath, 'org.js');
    $expectedPath = Path::changeExtension($jsPath, 'expected.js');
    copy($orgPath, $jsPath);

    $application = new PlaisioApplication();
    $application->setAutoExit(false);
    $tester = new ApplicationTester($application);
    $tester->run(['command' => 'plaisio:type-script-fixer',
                  'path'    => $jsPath]);

    $output = $tester->getDisplay();
    self::assertSame(0, $tester->getStatusCode(), $output);
    self::assertFileEquals($expectedPath, $jsPath);

    unlink($jsPath);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test no return statement is added for interfaces.
   */
  public function testInterface(): void
  {
    copy(Path::join(__DIR__, __FUNCTION__, 'plaisio-assets.xml'), Path::join(getcwd(), 'plaisio-assets.xml'));

    $jsPath       = Path::makeRelative(Path::join(__DIR__, __FUNCTION__, 'js', 'Test', 'Foo.js'), getcwd());
    $orgPath      = Path::changeExtension($jsPath, 'org.js');
    $expectedPath = Path::changeExtension($jsPath, 'expected.js');
    copy($orgPath, $jsPath);

    $application = new PlaisioApplication();
    $application->setAutoExit(false);
    $tester = new ApplicationTester($application);
    $tester->run(['command' => 'plaisio:type-script-fixer',
                  'path'    => $jsPath]);

    $output = $tester->getDisplay();
    self::assertSame(0, $tester->getStatusCode(), $output);
    self::assertFileEquals($expectedPath, $jsPath);

    unlink($jsPath);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test fixing defining deps list longer than arguments
   */
  public function testMoreDepsThanArguments(): void
  {
    copy(Path::join(__DIR__, __FUNCTION__, 'plaisio-assets.xml'), Path::join(getcwd(), 'plaisio-assets.xml'));

    $jsPath       = Path::makeRelative(Path::join(__DIR__, __FUNCTION__, 'js', 'Test', 'Foo.js'), getcwd());
    $orgPath      = Path::changeExtension($jsPath, 'org.js');
    $expectedPath = Path::changeExtension($jsPath, 'expected.js');
    copy($orgPath, $jsPath);

    $application = new PlaisioApplication();
    $application->setAutoExit(false);
    $tester = new ApplicationTester($application);
    $tester->run(['command' => 'plaisio:type-script-fixer',
                  'path'    => $jsPath]);

    $output = $tester->getDisplay();
    self::assertSame(0, $tester->getStatusCode(), $output);
    self::assertFileEquals($expectedPath, $jsPath);

    unlink($jsPath);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
