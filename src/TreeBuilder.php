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


    /**
     * Конструктор принимает интерфейсы для чтения и записи данных.
     *
     * @param DataReaderInterface $reader
     * @param DataWriterInterface $writer
     */
    public function __construct(DataReaderInterface $reader, DataWriterInterface $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    /**
     * Основной метод для построения дерева.
     * Читает данные, добавляет элементы, организует их в дерево, расширяет связи и удаляет ненужные поля.
     */
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

    /**
     * Добавляет элемент в массив items.
     */
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

    /**
     * Организует элементы в иерархическую структуру дерева.
     */
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

    /**
     * Расширяет дерево на основе связей (relation).
     */
    private function expandRelations(): void
    {
        foreach ($this->items as &$item) {
            if ($item['relation']) {
                $this->attachRelatedItems($item);
            }
        }
    }

    /**
     * Добавляет дочерние элементы связанного элемента к текущему элементу.
     *
     * @param array &$item
     */
    private function attachRelatedItems(array &$item): void
    {
        $relatedItemName = $item['relation'];
        if (isset($this->items[$relatedItemName])) {
            $relatedItem = &$this->items[$relatedItemName];
            foreach ($relatedItem['children'] as $child) {
                $newChild = $child;
                $newChild['parent'] = $item['itemName']; // Обновление родительской связи для дочернего элемента
                $item['children'][] = $newChild;
            }
        }
    }

    /**
     * Удаляет поле relation из всех элементов дерева.
     *
     * @param array $tree
     * @return array
     */
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
