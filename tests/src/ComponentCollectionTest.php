<?php

namespace Drieschel\UnitsOfMeasurement;

use PHPUnit\Framework\TestCase;

class ComponentCollectionTest extends TestCase
{

    /**
     * @param array $components
     * @throws ComponentCollectionException
     */
    public function testCount(AbstractComponent ...$components)
    {
        $expected = count($components);
        $collection = new ComponentCollection(...$components);
        $this->assertEquals($expected, $collection->count());
    }

    /**
     * @dataProvider symbolsIndexedComponentsProvider
     *
     * @param array $indexedBySymbols
     * @param AbstractComponent ...$components
     * @throws ComponentCollectionException
     */
    public function testCurrent(array $indexedBySymbols, AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        foreach ($collection as $i => $component) {
            $this->assertEquals($indexedBySymbols[$i], $collection->current());
        }
        $this->assertNull($collection->current());
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws ComponentCollectionException
     */
    public function testHas(AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        foreach ($components as $component) {
            $this->assertTrue($collection->has($component->getSymbol()));
            $this->assertFalse($collection->has($component->getName()));
        }
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws ComponentCollectionException
     */
    public function testMerge(AbstractComponent ...$components)
    {
        $firstCollection = new ComponentCollection();
        $secondCollection = new ComponentCollection(...$components);
        $firstCollection->merge($secondCollection);
        foreach ($components as $component) {
            $this->assertTrue($firstCollection->has($component->getSymbol()));
        }
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws ComponentCollectionException
     */
    public function testSet(AbstractComponent ...$components)
    {
        $collection = new ComponentCollection();
        foreach ($components as $component) {
            $this->assertFalse($collection->has($component->getSymbol()));
            $collection->set($component);
            $this->assertTrue($collection->has($component->getSymbol()));
        }
    }

    public function testSetAbstractComponentWithMultipleSymbols()
    {
        $symbol1 = uniqid('s1-');
        $symbol2 = uniqid('s2-');
        $symbol3 = uniqid('s3-');
        $collection = new ComponentCollection();
        $component = $this->getMockForAbstractClass(AbstractComponent::class, ['symbol1', $symbol1])->addSymbols($symbol2, $symbol3);
        $collection->set($component);
        $this->assertEquals($component, $collection->get($symbol1));
        $this->assertEquals($component, $collection->get($symbol2));
        $this->assertEquals($component, $collection->get($symbol3));
    }

    public function testSetComponentInterface()
    {
        $symbol = uniqid('symbol-');
        $collection = new ComponentCollection();
        $component = $this->createMock(ComponentInterface::class);
        $component->expects($this->any())->method('getSymbol')->willReturn($symbol);
        $collection->set($component);
        $this->assertEquals($component, $collection->get($symbol));
    }

    public function testSetSymbolExists()
    {
        $this->expectException(ComponentCollectionException::class);
        $this->expectExceptionCode(ComponentCollectionException::EXISTING_SYMBOL);
        $collection = new ComponentCollection();
        $symbol = uniqid('symbolix-');
        $component1 = $this->getMockForAbstractClass(AbstractComponent::class, ['name1', $symbol]);
        $component2 = $this->getMockForAbstractClass(AbstractComponent::class, ['name2', $symbol]);
        $collection->set($component1)->set($component2);
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws ComponentCollectionException
     */
    public function testValid(AbstractComponent ...$components)
    {
        $componentsCount = count($components);
        $collection = new ComponentCollection(...$components);
        for ($i = 0; $i < $componentsCount; $i++) {
            $this->assertTrue($collection->valid());
            $collection->next();
        }
        $this->assertFalse($collection->valid());
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws ComponentCollectionException
     */
    public function testFilterByClosure(AbstractComponent ...$components)
    {
        $max = mt_rand(1, 20);
        /** @var AbstractComponent[] $componentsToFilter */
        $componentsToFilter = [];
        for($i = 0; $i < $max; $i++) {
            $componentsToFilter[] = $this->getMockForAbstractClass(AbstractComponent::class, [uniqid('name-'), uniqid('foobar-')]);
        }

        $allComponents = array_merge($componentsToFilter, $components);
        shuffle($allComponents);

        $collection = new ComponentCollection(...$allComponents);

        $closure = function(AbstractComponent $component) {
            return substr($component->getSymbol(), 0, 6) === 'foobar';
        };

        $filteredCollection = $collection->filterByClosure($closure);
        $this->assertCount(count($componentsToFilter), $filteredCollection);
        foreach ($componentsToFilter as $component) {
            $this->assertTrue($filteredCollection->has($component->getSymbol()));
        }
    }

    /**
     * @dataProvider symbolsIndexedComponentsProvider
     *
     * @param array $sortedBySymbols
     * @param AbstractComponent ...$components
     * @throws ComponentCollectionException
     */
    public function testRewind(array $sortedBySymbols, AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        $stepUntil = mt_rand(1, count($sortedBySymbols) - 1);
        for($i = 0; $i < $stepUntil; $i++, $collection->next()) {
        }
        $this->assertNotEquals($components[0], $collection->current());
        $collection->rewind();
        $this->assertEquals($components[0], $collection->current());
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws ComponentCollectionException
     */
    public function testGet(AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        foreach($components as $component) {
            foreach($component->getSymbols() as $symbol) {
                $this->assertEquals($component, $collection->get($symbol));
            }
        }
    }

    public function testGetSymbolNotFound()
    {
        $this->expectException(ComponentCollectionException::class);
        $this->expectExceptionCode(ComponentCollectionException::UNKNOWN_SYMBOL);
        $collection = new ComponentCollection();
        $collection->get(uniqid('symboliax'));
    }

    /**
     * @dataProvider componentsProvider
     *
     * @param AbstractComponent ...$components
     * @throws ComponentCollectionException
     */
    public function testKey(AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        foreach($components as $component) {
            foreach($component->getSymbols() as $symbol) {
                $this->assertEquals($symbol, $collection->key());
                $collection->next();
            }
        }
    }

    /**
     * @dataProvider symbolsIndexedComponentsProvider
     *
     * @param array $sortedBySymbols
     * @param AbstractComponent ...$components
     * @throws ComponentCollectionException
     */
    public function testNext(array $sortedBySymbols, AbstractComponent ...$components)
    {
        $collection = new ComponentCollection(...$components);
        foreach($sortedBySymbols as $component) {
            $this->assertEquals($component, $collection->current());
            $collection->next();
        }
    }

    /**
     * @return AbstractComponent[]
     */
    public function componentsProvider(): array
    {
        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[] = array_map(function ($null) {
                return $this->getMockForAbstractClass(AbstractComponent::class, [uniqid('name-'), uniqid('symbol-')]);
            }, array_fill(0, mt_rand(1, 20), null));
        }
        return $data;
    }

    /**
     * @return AbstractComponent[]
     */
    public function symbolsIndexedComponentsProvider(): array
    {
        $components = $this->componentsProvider();
        $rowCount = count($components);
        $resortedComponents = [];
        for ($i = 0; $i < $rowCount; $i++) {
            $resortedComponents[$i] = [];
            foreach ($components[$i] as $component) {
                foreach ($component->getSymbols() as $symbol) {
                    $resortedComponents[$i][0][$symbol] = $component;
                }
            }
            $resortedComponents[$i] = array_merge($resortedComponents[$i], $components[$i]);
        }
        return $resortedComponents;
    }

    /**
     * @return AbstractComponent[][]
     */
    public function twoComponentArraysProvider(): array
    {
        $max = mt_rand(1, 5);
        $data = [];
        for ($i = 0; $i < $max; $i++) {
            $data[] = [$this->componentsProvider(), $this->componentsProvider()];
        }
        return $data;
    }
}
