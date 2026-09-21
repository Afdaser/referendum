<?php

use frontend\assets\AppAsset;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../frontend/assets/AppAsset.php';

class FrontendCssLoadingTest extends TestCase
{
    public function testCriticalStylesAreSynchronousAndNotDuplicatedInNoscript(): void
    {
        $asset = (new ReflectionClass(AppAsset::class))->newInstanceWithoutConstructor();
        $criticalStyles = [
            '/css/normalize.min.css',
            '/css/jquery.bxslider.css',
            '/css/custom.css',
        ];

        // Критичні стилі належать звичайному масиву AssetBundle без глобальних опцій.
        $cssOptionsProperty = new ReflectionProperty($asset, 'cssOptions');
        $this->assertNotSame(AppAsset::class, $cssOptionsProperty->getDeclaringClass()->getName());
        $this->assertSame([], $asset->cssOptions);
        foreach ($criticalStyles as $style) {
            $this->assertContains($style, $asset->css);
        }

        $layout = file_get_contents(__DIR__ . '/../frontend/views/layouts/main.php');
        preg_match('/<noscript>(.*?)<\/noscript>/s', $layout, $noscript);
        $this->assertNotEmpty($noscript);
        foreach ($criticalStyles as $style) {
            $this->assertStringNotContainsString($style, $noscript[1]);
        }
    }
}
