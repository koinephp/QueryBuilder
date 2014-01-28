<?php

require_once 'Gs/QueryBuilder/Update.php';

class Gs_QueryBuilder_UpdateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Gs_QueryBuilder_Update
     */
    protected $o;

    public function setUp()
    {
        $this->o = new Gs_QueryBuilder_Update();
    }

    /**
     * @test
     */
    public function itInitializesWithTheCorrectUpdateStatement()
    {
        $this->assertInstanceOf('Gs_QueryBuilder_UpdateStatement', $this->o->getUpdate());
    }

    /**
     * @test
     */
    public function itInitializesWithTheCorrectSetStatement()
    {
        $this->assertInstanceOf('Gs_QueryBuilder_SetStatement', $this->o->getSet());
    }

    /**
     * @test
     */
    public function itInitializesWithTheCorrectWhereStatement()
    {
        $this->assertInstanceOf('Gs_QueryBuilder_WhereStatement', $this->o->getWhere());
    }

    /**
     * @test
     */
    public function itInitializesWithTheCorrectOrderStatement()
    {
        $this->assertInstanceOf('Gs_QueryBuilder_OrderStatement', $this->o->getOrder());
    }

    /**
     * @test
     */
    public function itInitializesWithTheCorrectJoinsStatement()
    {
        $this->assertInstanceOf('Gs_QueryBuilder_JoinStatement', $this->o->getJoins());
    }

    /**
     * @test
     */
    public function itCanOverrideTheHelperCorrectHelper()
    {
        $this->assertInstanceOf('Gs_QueryBuilder_Helper', $this->o->getHelper());
    }

    /**
     * @test
     */
    public function itInitializesWithTheCorrectHelper()
    {
        $helper = new Gs_QueryBuilder_Helper();
        $options = array('helper' => $helper);
        $object = new Gs_QueryBuilder($options);
        $this->assertSame($helper, $object->getHelper());
    }

    /**
     * @test
     */
    public function itCanConvertQueryToString()
    {
        $sql = 'UPDATE table';
        $this->o->table('table');
        $this->assertEquals($sql, $this->o->toSql());

        $sql .= ' INNER JOIN table2';
        $this->o->innerJoin('table2');
        $this->assertEquals($sql, $this->o->toSql());

        $sql .= ' SET foo = "bar", age = 12';
        $this->o->set(array(
            'foo' => 'bar',
            'age' => 12
        ));
        $this->assertEquals($sql, $this->o->toSql());

        $sql .= ' WHERE a = "b" AND b = 1';
        $this->o->where('a', 'b')->where(array('b' => 1));
        $this->assertEquals($sql, $this->o->toSql());

        $sql .= ' ORDER BY foo, bar DESC';
        $this->o->orderBy(array('foo', 'bar DESC'));
        $this->assertEquals($sql, $this->o->toSql());
    }

    /**
     * @test
     */
    public function itGetSqlReplacingThePlaceHolders()
    {
        $this->o->table('table')->set(array(
            'name' => ':name',
            'age'  => ':age'
        ));

        $sql = 'UPDATE table SET name = "foo", age = 12';
        $params = array(
            'name' => 'foo',
            'age' => 12
        );

        $this->assertEquals($sql, $this->o->toSql($params));
    }


    /**
     * @test
     */
    public function itAddsInnerJoin()
    {
        $this->o->innerJoin('t1')->innerJoin('t2', 't1.id = t2.t1_id');
        $sql = 'INNER JOIN t1 INNER JOIN t2 ON t1.id = t2.t1_id';
        $this->assertEquals($sql, $this->o->getJoins()->toSql());
    }

    /**
     * @test
     */
    public function itAddsLeftJoin()
    {
        $this->o->leftJoin('t1')->leftJoin('t2', 't1.id = t2.t1_id');
        $sql = 'LEFT JOIN t1 LEFT JOIN t2 ON t1.id = t2.t1_id';
        $this->assertEquals($sql, $this->o->getJoins()->toSql());
    }

    /**
     * @test
     */
    public function itCallsToSqlWhenConvertingToString()
    {
        $this->assertEquals($this->o->toSql(), (string) $this->o);
    }

    /**
     * @test
     */
    public function itProvidesInterfaceForAddingConditions()
    {
        $this->o->where('a = 1')
            ->where('a', 'b')
            ->where('a', 'x', '!=')
            ->where(array(
                'foo' => 'bar',
                'foobar' => 'foo'
            ));

        $expectedParams = array(
            'a = 1',
            'a = "b"',
            'a != "x"',
            'foo = "bar"',
            'foobar = "foo"',
        );

        $this->assertEquals($expectedParams, $this->o->getWhere()->getParams());
    }

    /**
     * @test
     */
    public function itProvidesInterfaceForAddingOrder()
    {
        $this->o->orderBy('a')->orderBy(array('b', 'c'));

        $expectedParams = array( 'a', 'b', 'c');

        $this->assertEquals($expectedParams, $this->o->getOrder()->getParams());
    }

}