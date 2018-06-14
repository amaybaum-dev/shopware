<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\Event;

use Shopware\Core\Content\Category\Event\CategoryBasicLoadedEvent;
use Shopware\Core\Content\Configuration\Aggregate\ConfigurationGroupOption\Event\ConfigurationGroupOptionBasicLoadedEvent;
use Shopware\Core\Content\Product\Aggregate\ProductConfigurator\Event\ProductConfiguratorBasicLoadedEvent;
use Shopware\Core\Content\Product\Aggregate\ProductManufacturer\Event\ProductManufacturerBasicLoadedEvent;
use Shopware\Core\Content\Product\Aggregate\ProductMedia\Collection\ProductMediaBasicCollection;
use Shopware\Core\Content\Product\Aggregate\ProductMedia\Event\ProductMediaBasicLoadedEvent;
use Shopware\Core\Content\Product\Aggregate\ProductSearchKeyword\Event\ProductSearchKeywordBasicLoadedEvent;
use Shopware\Core\Content\Product\Aggregate\ProductService\Event\ProductServiceBasicLoadedEvent;
use Shopware\Core\Content\Product\Aggregate\ProductStream\Event\ProductStreamBasicLoadedEvent;
use Shopware\Core\Content\Product\Aggregate\ProductTranslation\Event\ProductTranslationBasicLoadedEvent;
use Shopware\Core\Content\Product\Collection\ProductDetailCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\NestedEvent;
use Shopware\Core\Framework\Event\NestedEventCollection;
use Shopware\Core\System\Tax\Event\TaxBasicLoadedEvent;
use Shopware\Core\System\Unit\Event\UnitBasicLoadedEvent;

class ProductDetailLoadedEvent extends NestedEvent
{
    public const NAME = 'product.detail.loaded';

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var ProductDetailCollection
     */
    protected $products;

    public function __construct(ProductDetailCollection $products, Context $context)
    {
        $this->context = $context;
        $this->products = $products;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getProducts(): ProductDetailCollection
    {
        return $this->products;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [];

        $media = new ProductMediaBasicCollection(array_merge(
            $this->products->getMedia()->getElements(),
            $this->products->getCovers()->getElements()
        ));

        if ($this->products->getParents()->count() > 0) {
            $events[] = new ProductBasicLoadedEvent($this->products->getParents(), $this->context);
        }
        if ($this->products->getTaxes()->count() > 0) {
            $events[] = new TaxBasicLoadedEvent($this->products->getTaxes(), $this->context);
        }
        if ($this->products->getManufacturers()->count() > 0) {
            $events[] = new ProductManufacturerBasicLoadedEvent($this->products->getManufacturers(), $this->context);
        }
        if ($this->products->getUnits()->count() > 0) {
            $events[] = new UnitBasicLoadedEvent($this->products->getUnits(), $this->context);
        }
        if ($this->products->getChildren()->count() > 0) {
            $events[] = new ProductBasicLoadedEvent($this->products->getChildren(), $this->context);
        }
        if ($media->count() > 0) {
            $events[] = new ProductMediaBasicLoadedEvent($media, $this->context);
        }
        if ($this->products->getSearchKeywords()->count() > 0) {
            $events[] = new ProductSearchKeywordBasicLoadedEvent($this->products->getSearchKeywords(), $this->context);
        }
        if ($this->products->getTranslations()->count() > 0) {
            $events[] = new ProductTranslationBasicLoadedEvent($this->products->getTranslations(), $this->context);
        }
        if ($this->products->getAllCategories()->count() > 0) {
            $events[] = new CategoryBasicLoadedEvent($this->products->getAllCategories(), $this->context);
        }
        if ($this->products->getAllSeoCategories()->count() > 0) {
            $events[] = new CategoryBasicLoadedEvent($this->products->getAllSeoCategories(), $this->context);
        }
        if ($this->products->getAllTabs()->count() > 0) {
            $events[] = new ProductStreamBasicLoadedEvent($this->products->getAllTabs(), $this->context);
        }
        if ($this->products->getAllStreams()->count() > 0) {
            $events[] = new ProductStreamBasicLoadedEvent($this->products->getAllStreams(), $this->context);
        }
        if ($this->products->getConfigurators()->count() > 0) {
            $events[] = new ProductConfiguratorBasicLoadedEvent($this->products->getConfigurators(), $this->context);
        }
        if ($this->products->getServices()->count() > 0) {
            $events[] = new ProductServiceBasicLoadedEvent($this->products->getServices(), $this->context);
        }
        if ($this->products->getAllDatasheets()->count() > 0) {
            $events[] = new ConfigurationGroupOptionBasicLoadedEvent($this->products->getAllDatasheets(), $this->context);
        }
        if ($this->products->getAllVariations()->count() > 0) {
            $events[] = new ConfigurationGroupOptionBasicLoadedEvent($this->products->getAllVariations(), $this->context);
        }

        return new NestedEventCollection($events);
    }
}
