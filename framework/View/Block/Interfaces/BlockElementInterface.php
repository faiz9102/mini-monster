<?php

namespace Framework\View\Block;

interface BlockElementInterface
{
/**
     * Get the children of the block element.
     *
     * @return BlockElementInterface[]
     */
    public function getChildren(): array;

    /**
     * Set the children of the block element.
     *
     * @param BlockElementInterface[] $children
     * @return self
     */
    public function setChildren(array $children): self;

    /**
     * Get the templates associated with the block element.
     *
     * @return string|null
     */
    public function getTemplate(): ?string;

    /**
     * Set the templates for the block element.
     *
     * @param string|null $template
     * @return self
     */
    public function setTemplate(?string $template): self;

    /**
     * Get the name of the block element.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set the name of the block element.
     *
     * @param string $name
     * @return self
     */
    public function setName(string $name): self;

    /**
     * Get the data associated with the block element.
     *
     * @return array
     */
    public function getData(): array;
}