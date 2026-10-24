<?php

declare(strict_types=1);

namespace StudioMitte\FriendlyCaptcha\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ComposerAutoloadScriptsTest extends TestCase
{
    #[Test]
    public function declaredComposerClassCallbacksAreCallable(): void
    {
        $composer = json_decode(file_get_contents(dirname(__DIR__, 2) . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);
        $callbacks = $composer['scripts']['post-autoload-dump'] ?? [];
        $callbacks = is_string($callbacks) ? [$callbacks] : $callbacks;
        $unavailable = [];
        foreach ($callbacks as $callback) {
            // Composer also accepts shell commands; this contract concerns its
            // class callbacks, which Composer otherwise warns about but skips.
            if (!str_starts_with($callback, '@') && str_contains($callback, '::') && !str_contains($callback, ' ') && !is_callable($callback)) {
                $unavailable[] = $callback;
            }
        }
        self::assertSame([], $unavailable, 'Composer must be able to execute every declared autoload callback.');
    }
}
