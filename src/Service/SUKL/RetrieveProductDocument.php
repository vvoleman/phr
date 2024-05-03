<?php declare(strict_types=1);

namespace App\Service\SUKL;

use App\Entity\MedicalProduct;
use App\Service\SUKL\Exception\SuklException;
use App\Service\Util\LoggerTrait;
use Symfony\Component\DomCrawler\Crawler;

class RetrieveProductDocument
{

	use LoggerTrait;

	protected const LINK_JSON = 'https://prehledy.sukl.cz/prehledy/v1/dlprc/list/%s';
	protected const LINK_PDF = 'https://prehledy.sukl.cz/prehledy/v1/dokumenty/%s';
	protected const SELECTOR = '.sukl-chip-group.sukl-chip-group--wrap a';

	/**
	 * @throws SuklException If the document URL cannot be retrieved for any reason.
	 */
	public function getDocumentUrl(MedicalProduct $product): ?string
	{
		$url = sprintf(self::LINK_JSON, $product->getId());
		try {
			$html = file_get_contents($url);
		} catch (\Exception) {
			throw new SuklException("Unable to access URL '" . $url . "'.");
		}

		$json = json_decode($html, true);
		if ($json === null) {
			$this->getLogger()->error("Unable to parse JSON from URL: " . $url);
			throw new SuklException("Unable to parse JSON from URL '" . $url . "'.");
		}

		return $this->crawlDocument($json, $product);
	}

	private function crawlDocument(array $json, MedicalProduct $medicalProduct): ?string
	{
		$id = $medicalProduct->getId();
		$item = array_filter($json, function ($item) use ($id) {
			return $item['kodSUKL'] === $id;
		});

		if (count($item) === 0) {
			$this->getLogger()->error("No item found for product ID: " . $id);
			return null;
		}

		$item = array_values($item)[0];
		$pil = array_filter($item["dokumenty"], function ($item) {
			return $item['typDokumentu'] === 'PIL';
		});

		if (count($pil) === 0) {
			$this->getLogger()->error("No PIL found for product ID: " . $id);
			return null;
		}

		$pil = array_values($pil)[0];
		$pilId = $pil['id'];

		$url = sprintf(self::LINK_PDF, $pilId);
		$this->getLogger()->info("Found PIL for product ID: " . $id);

		return $url;
	}

}
