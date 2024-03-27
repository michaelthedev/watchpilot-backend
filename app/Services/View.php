<?php

namespace App\Services;

use App\Exceptions\ViewNotFoundException;

/**
 * View Service Class
 * @author Michael Arawole <michael@logad.net>
 * @package App\Services
 */
final class View
{
	private string $basePath
		= VIEWS_PATH.DIRECTORY_SEPARATOR;
	public string $view;
	private string $viewFile;

	/**
	 * Files to include before the view
	 * @var array
	 */
	private array $includeBefore = [];

	/**
	 * Files to include after the view
	 * @var array
	 */
	private array $includeAfter = [];

	public function __construct(string $view)
	{
		$this->view = $view;
		$this->viewFile = str_replace('.', '/', $view) . '.php';
	}

	public function setBasePath(string $path): self
	{
		$this->basePath = $path.DIRECTORY_SEPARATOR;
		return $this;
	}

	private function fileExists(): bool
	{
		return file_exists($this->basePath . $this->viewFile);
	}

	/**
	 * Include a file before view is included
	 * Example: header, navbar
	 * @param string $filePath
	 * @return $this
	 */
	public function includeBefore(string $filePath): self
	{
		$this->includeBefore[] = $filePath;
		return $this;
	}

	/**
	 * Include a file after view is included
	 * Example: footer
	 * @param string $filePath
	 * @return $this
	 */
	public function includeAfter(string $filePath): self
	{
		$this->includeAfter[] = $filePath;
		return $this;
	}

	/**
	 * Include added files and view file
	 * @throws ViewNotFoundException
	 * @return $this
	 */
	public function render(array $variables = []): self
	{
		if (!$this->fileExists()) {
			throw new ViewNotFoundException($this->view);
		}

		// Include page variables
		extract($variables);

		foreach ($this->includeBefore as $file) {
			include $this->basePath . $file;
		}

		// Include view file
		include $this->basePath . $this->viewFile;

		foreach ($this->includeAfter as $file) {
			include $this->basePath . $file;
		}
		return $this;
	}

	public function error(int $code): void
	{
		$view = self::make("errors.$code")
			->setBasePath($this->basePath)
			->render();
	}

	public static function make(string $view): self
	{
		return new self($view);
	}

	public static function exits(string $view): bool
	{
		return (new self($view))
			->fileExists();
	}

	public function __toString(): string
	{
		return '';
	}
}