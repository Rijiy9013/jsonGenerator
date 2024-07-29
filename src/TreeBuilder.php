<?php

namespace App;

use App\Contracts\DataReaderInterface;
use App\Contracts\DataWriterInterface;

class TreeBuilder
{
    private array $items = [];
    private array $tree = [];
    private DataReaderInterface $reader;
    private DataWriterInterface $writer;

    public function __construct(DataReaderInterface $reader, DataWriterInterface $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function buildTree(): void
    {
        $this->reader->read(function ($data) {
            $this->addItem($data);
        });

        $this->organizeItemsIntoTree();
        $this->expandRelations();
        $tree = $this->removeRelation($this->tree);

        $this->writer->write($tree);
    }

    private function addItem(array $row): void
    {
        list($itemName, $type, $parent, $relation) = $row;
        $this->items[$itemName] = [
            'itemName' => $itemName,
            'parent' => $parent ? $parent : null,
            'relation' => $relation,
            'children' => []
        ];
    }

    private function organizeItemsIntoTree(): void
    {
        foreach ($this->items as &$item) {
            if ($item['parent']) {
                $this->items[$item['parent']]['children'][] = &$item;
            } else {
                $this->tree[] = &$item;
            }
        }
    }

    private function expandRelations(): void
    {
        foreach ($this->items as &$item) {
            if ($item['relation']) {
                $this->attachRelatedItems($item);
            }
        }
    }

    private function attachRelatedItems(array &$item): void
    {
        $relatedItemName = $item['relation'];
        if (isset($this->items[$relatedItemName])) {
            $relatedItem = &$this->items[$relatedItemName];
            foreach ($relatedItem['children'] as $child) {
                $newChild = $child;
                $newChild['parent'] = $item['itemName']; // Update parent to reflect new relationship
                $item['children'][] = $newChild;
            }
        }
    }

    private function removeRelation(array $tree): array
    {
        foreach ($tree as &$item) {
            unset($item['relation']);
            if (!empty($item['children'])) {
                $item['children'] = $this->removeRelation($item['children']);
            }
        }
        return $tree;
    }
}
