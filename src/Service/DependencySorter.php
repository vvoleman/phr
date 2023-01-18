<?php

namespace App\Service;

class DependencySorter
{

	/**
	 * @param AbstractSyncer[] $syncers
	 * @return array<class-string<AbstractSyncer>>
	 */
	public function sort(array $syncers): array
	{
		// First we need to create a graph of dependencies
		$graph = [];
		foreach ($syncers as $syncer) {
			$graph[$syncer] = $syncer::getDependencies();

			foreach ($syncer::getDependencies() as $dependency) {
				if (!isset($graph[$dependency])) {
					$graph[$dependency] = [];
				}
			}
		}

		// Now we can use the topological sort algorithm to sort the syncers
		$sorted = [];
		$visited = [];
		foreach ($graph as $node => $dependencies) {
			$this->visit($node, $graph, $visited, $sorted);
		}

		return $sorted;
	}

	/**
	 * @param class-string<AbstractSyncer> $node
	 * @param array<class-string<AbstractSyncer>, array<class-string<AbstractSyncer>>> $graph
	 * @param array<class-string<AbstractSyncer>> $visited
	 * @param array<class-string<AbstractSyncer>> $sorted
	 */
	private function visit(string $node, array $graph, array &$visited, array &$sorted): void
	{
		if (in_array($node, $visited, true)) {
			return;
		}

		$visited[] = $node;

		foreach ($graph[$node] as $dependency) {
			$this->visit($dependency, $graph, $visited, $sorted);
		}

		$sorted[] = $node;
	}

}