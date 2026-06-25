<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Template\Components\Buttons;

use TYPO3\CMS\Backend\Template\Components\Buttons\AbstractButton;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class Ai3LinkButton extends AbstractButton
{
    protected string $href = '';

    public function getHref(): string
    {
        return $this->href;
    }

    public function setHref(string $href): self
    {
        $this->href = $href;
        return $this;
    }

    public function isValid(): bool
    {
        if (
            trim($this->getHref()) !== ''
            && trim($this->getTitle()) !== ''
            && $this->getType() === self::class
            && $this->getIcon() !== null
        ) {
            return true;
        }
        return false;
    }

    public function render(): string
    {
        $attributes = [
            'href' => $this->getHref(),
            'class' => 'btn ' . $this->getClasses(),
            'title' => $this->getTitle(),
        ];
        $labelText = '';
        if ($this->showLabelText) {
            $labelText = ' ' . $this->title;
        }
        foreach ($this->dataAttributes as $attributeName => $attributeValue) {
            $attributes['data-' . $attributeName] = $attributeValue;
        }
        if ($this->isDisabled()) {
            $attributes['disabled'] = 'disabled';
            $attributes['class'] .= ' disabled';
        }
        $attributesString = GeneralUtility::implodeAttributes($attributes, true);

        return '<a ' . $attributesString . '>'
            . $this->getIcon()->render() . htmlspecialchars($labelText)
            . '</a>';
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
