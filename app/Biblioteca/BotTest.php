<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 17/11/2017
 * Time: 05:36 PM
 */

namespace App\Biblioteca;

use App\Biblioteca\Sunat\Bot\Bot;
use App\Biblioteca\Sunat\Bot\Menu;
use App\Biblioteca\Sunat\Bot\Model\ClaveSol;

class BotTest 
{
    /**
     * @var Bot
     */
    private $bot;

    public function setUp($ruc,$usuario,$clave)
    {
        $user = new ClaveSol();
        $user->ruc = $ruc;
        $user->user = $usuario;
        $user->password = $clave;


        $this->bot = new Bot($user);
    }

    public function testLogin()
    {
        $this->assertTrue($this->bot->login());
    }

    public function testGetList($start,$end)
    {
        $this->bot->login();
        

        $this->bot->navigate([Menu::CONSULTA_SOL_FACTURA]);

        $sales = $this->bot->getVentas($start, $end);


        return $sales;
        //dd($sales)
        //$this->assertGreaterThanOrEqual(0, count($sales));
    }

    public function testGetListBol()
    {
        $this->bot->login();
        $this->bot->navigate([Menu::CONSULTA_SOL_BOLETA]);
        $start = '01/08/2017';
        $end = '24/08/2017';
        $sales = $this->bot->getVentasBol($start, $end);

        $this->assertEquals(0, count($sales));
    }

    public function testGetSee()
    {
        $this->bot->login();
        $this->bot->navigate([Menu::CONSULTA_SEE_FE]);
        $doc = $this->bot->getVentaSee('F001', '1');

        $this->assertNotNull($doc);
    }

    public function testGetXmlSee()
    {
        $ruc = getenv('COMPANY_RUC_EMISOR');
        $this->bot->login();
        $this->bot->navigate([Menu::CONSULTA_SEE_FE]);
        $xml = $this->bot->getSeeXml($ruc,'F001', '184');

        $this->assertNotEmpty($xml);
    }

    public function testGetXmlFac($rucEmisor,$serie,$numero,$nombre_archivo)
    {
        $rucEmisor = $rucEmisor;
        $this->bot->login();
        $this->bot->navigate([Menu::CONSULTA_SOL_FACTURA]);
        $xml = $this->bot->getXmlFac($rucEmisor, $serie, $numero);
        file_put_contents('facturas/'.$nombre_archivo, $xml);



        //$this->assertNotEmpty($xml);
    }

    public function testNotFoundXml($rucEmisor,$serie,$numero)
    {
        $rucEmisor = $rucEmisor;
        $this->bot->login();
        $this->bot->navigate([Menu::CONSULTA_SOL_FACTURA]);
        $xml = $this->bot->getXmlFac($rucEmisor, $serie, $numero);

        $this->assertFalse($xml);
        file_put_contents('factura'.$numero.'.xml', $xml);
    }
}