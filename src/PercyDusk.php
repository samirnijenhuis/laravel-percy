<?php declare(strict_types=1);

namespace Letspaak\LaravelPercy;

use Illuminate\Http\Client\Factory;
use Laravel\Dusk\Browser;
use Letspaak\LaravelPercy\Contracts\Percy as PercyContract;
use RuntimeException;

class PercyDusk implements PercyContract
{
    protected string $domJs;

    public function __construct(protected Factory $http, protected Browser $browser)
    {
    }

    public function snapshot(string $name, ?array $widths = null, ?int $minHeight = null, bool $enableJavaScript = true, ?string $percyCSS = null): void
    {
        if (!$this->isPercyEnabled()) {
            return;
        }

        $this->browser->script($this->fetchPercyDOM());
        $domSnapshot = $this->browser->script($this->buildSnapshotJS($enableJavaScript));

        $this->submitSnapshot($domSnapshot, $name, $widths, $minHeight, $this->browser->driver->getCurrentURL(), $enableJavaScript, $percyCSS);
    }

    protected function getAddress(): string
    {
        return config('percy.server_address');
    }

    protected function fetchPercyDOM(): string
    {
        if (isset($this->domJs)) {
            return $this->domJs;
        }

        $response = $this->http->get("{$this->getAddress()}/percy/dom.js");

        if ($response->status() !== 200) {
            throw new RuntimeException("Failed with HTTP error code: " . $response->status());
        }

        $this->domJs = $response->body();

        return $this->domJs;
    }

    protected function buildSnapshotJS(bool $enableJavaScript): string
    {
        return sprintf("PercyDOM.serialize(%s)\n", json_encode(compact('enableJavaScript')));
    }

    protected function isPercyEnabled(): bool
    {
        $response = $this->http->get("{$this->getAddress()}/percy/healthcheck");
        if ($response->status() !== 200) {
            throw new RuntimeException("Failed with HTTP error code : " . $response->status());
        }

        $version = $response->header('X-Percy-Core-Version');
        if ($version === null) {
            echo "You may be using @percy/agent which is no longer supported by this SDK.\r\n" .
                "Please uninstall @percy/agent and install @percy/cli instead. \r\n" .
                "https://docs.percy.io/docs/migrating-to-percy-cli";

            return false;
        }
        if (!str_starts_with($version, "1.")) {
            echo "Unsupported Percy CLI version, {$version}";

            return false;
        }

        return true;
    }

    protected function submitSnapshot(array $domSnapshot, string $name, ?array $widths, ?int $minHeight, string $url, bool $enableJavaScript, ?string $percyCSS): void
    {
        if (!$this->isPercyEnabled()) {
            return;
        }

        $json = [
            "url" => $url,
            "name" => $name,
            "percyCSS" => $percyCSS,
            "minHeight" => $minHeight,
            "domSnapshot" => $domSnapshot,
            "clientInfo" => "percy-php-selenium/1.0.0",
            "enableJavaScript" => $enableJavaScript,
            "environmentInfo" => "selenium-php: App\\Web\\Percy",
            "widths" => $widths,
        ];

        $this->http->post($this->getAddress() . '/percy/snapshot', $json);
    }
}
