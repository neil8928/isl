<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 17/11/2017
 * Time: 05:12 PM
 */

namespace App\Biblioteca\Sunat\Bot;

use Curl\CaseInsensitiveArray;
use DOMDocument;
use App\Biblioteca\Sunat\Bot\Helper\ZipReader;
use App\Biblioteca\Sunat\Bot\Model\ClaveSol;
use App\Biblioteca\Sunat\Bot\Model\RrhhResult;
use App\Biblioteca\Sunat\Bot\Model\SaleResult;
use App\Biblioteca\Sunat\Bot\Model\SaleSeeResult;
use App\Biblioteca\Sunat\Bot\Request\CookieRequest;

/**
 * Class Bot
 * @package Sunat\Bot
 */
class Bot
{
    const URL_AUTH = 'https://api-seguridad.sunat.gob.pe/v1/clientessol/4f3b88b3-d9d6-402a-b85d-6a0bc857746a/oauth2/j_security_check';
    const URL_FORMAT_FE = 'https://ww1.sunat.gob.pe/ol-ti-itconscpemype/consultar.do?action=realizarConsulta&buscarPor=porPer&estado=1&fec_desde=%s&fec_hasta=%s&tipoConsulta=11';
    const URL_FORMAT_BE = 'https://ww1.sunat.gob.pe/ol-ti-itconscpemypebve/consultar.do?action=realizarConsulta&buscarPor=porPer&estado=1&fec_desde=%s&fec_hasta=%s&tipoConsulta=17';
    const URL_DOWNLOAD_XML_FAC = 'https://ww1.sunat.gob.pe/ol-ti-itconscpemype/consultar.do';
    const URL_DOWNLOAD_XML_BOL = 'https://ww1.sunat.gob.pe/ol-ti-itconscpemypebve/consultar.do';

    // SEE VENTAS
    const URL_SEE_CS = 'https://ww1.sunat.gob.pe/ol-ti-itconscpegem/consultar.do';
    const URL_SEE_XML = 'https://ww1.sunat.gob.pe/ol-ti-itconscpegem/consultar.do?action=descargarFactura&ruc=%s&tipo=10&serie=%s&numero=%s&isGEM=isGEM';

    /**
     * @var ClaveSol
     */
    private $user;
    /**
     * @var CookieRequest
     */
    private $req;

    /**
     * Bot constructor.
     * @param ClaveSol $user
     */
    public function __construct(ClaveSol $user)
    {
        $this->user = $user;
        $this->req = new CookieRequest();
    }

    /**
     * @return bool
     */
    public function login()
    {
        $curl = $this->req->getCurl();

        $curl->post(self::URL_AUTH, [
            'tipo' => '2',
            'dni' => '',
            'custom_ruc' => $this->user->ruc,
            'j_username' => $this->user->user,
            'j_password' => $this->user->password,
            'captcha' => '',
            'originalUrl' => 'https://e-menu.sunat.gob.pe/cl-ti-itmenu/AutenticaMenuInternet.htm',
            'state' => 'rO0ABXNyABFqYXZhLnV0aWwuSGFzaE1hcAUH2sHDFmDRAwACRgAKbG9hZEZhY3RvckkACXRocmVzaG9sZHhwP0AAAAAAAAx3CAAAABAAAAADdAAEZXhlY3B0AAZwYXJhbXN0AEsqJiomL2NsLXRpLWl0bWVudS9NZW51SW50ZXJuZXQuaHRtJmI2NGQyNmE4YjVhZjA5MTkyM2IyM2I2NDA3YTFjMWRiNDFlNzMzYTZ0AANleGVweA'
        ]);





        /**@var $headers CaseInsensitiveArray*/
        $headers = $curl->responseHeaders;



        if (!isset($headers['Location'])) {
            return false;
        }

        $this->navigate([$headers['Location']]);

        //dd($this->navigate([$headers['Location']]));

        return true;
    }

    /**
     * @param string $start
     * @param string $end
     * @return SaleResult[]
     */
    public function getVentas($start, $end)
    {
        $url = sprintf(self::URL_FORMAT_FE, urlencode($start), urlencode($end));
        $curl = $this->req->getCurl();

        $html = $curl->get($url);

        $all = [];
        $objs = $this->getList($html);



        foreach ($objs as $item) {
            //dd($item);
            $all[] = SaleResult::createFromArray($item);
        }

        //dd("holis");
        return $all;
    }

    /**
     * @param string $start
     * @param string $end
     * @return SaleResult[]
     */
    public function getVentasBol($start, $end)
    {
        $url = sprintf(self::URL_FORMAT_BE, urlencode($start), urlencode($end));
        $curl = $this->req->getCurl();
        $html = $curl->get($url);

        $all = [];
        $objs = $this->getList($html);
        foreach ($objs as $item) {
            $all[] = SaleResult::createFromArray($item);
        }

        return $all;
    }

    /**
     * Get Venta emitida desde el sistema del contribuyente.
     * @param string $serie
     * @param string $correlativo
     * @return SaleSeeResult
     */
    public function getVentaSee($serie, $correlativo)
    {
        $params = [
            'action' => 'realizarConsulta',
            'buscarPor' => 'porDoc',
            'tipoConsulta' => '10',
            'rucEmisor' => '',
            'numDocideRecep' => '',
            'serie' => $serie,
            'numero' => $correlativo,
            'fecDesde' => '',
            'fecHasta' => '',
        ];

        $curl = $this->req->getCurl();
        $html = $curl->post(self::URL_SEE_CS, $params);

        $objs = $this->getList($html);
        if (count($objs) == 0) {
            return null;
        }

        return SaleSeeResult::createFromArray($objs[0]);
    }

    /**
     * @param $start
     * @param $end
     * @return RrhhResult[]
     */
    public function getRrhh($start, $end)
    {
        $curl = $this->req->getCurl();
        /*$html = $curl->post('https://ww1.sunat.gob.pe/ol-ti-itreciboelectronico/cpelec001Alias', [
            'accion' => 'CapturaCriterioBusqueda1',
            'proceso' => '31196ALTA',
            'indicadoralta' => 'PNAT',
            'tipocomprobante' => '01;',
            'cod_docide' => '-',
            'num_docide' => '',
            'num_serie' => '',
            'num_comprob' => '',
            'fec_desde' => '01/08/2017',
            'fec_hasta' => '24/08/2017',
            'tipoestado' => '00',
            'tipocomprobante1' => '01',
        ]);*/

        $start = urlencode($start);
        $end = urlencode($end);

        $data = "accion=CapturaCriterioBusqueda1&proceso=31196ALTA&indicadoralta=PNAT&tipocomprobante=01%3B02%3B03%3B&cod_docide=-&num_docide=&num_serie=&num_comprob=&fec_desde=$start&fec_hasta=$end&tipoestado=00&tipocomprobante1=01&tipocomprobante1=02&tipocomprobante1=03";
//
        $curl->setUrl('https://ww1.sunat.gob.pe/ol-ti-itreciboelectronico/cpelec001Alias');
        $curl->setOpt(CURLOPT_POST, true);
        $curl->setOpt(CURLOPT_POSTFIELDS, $data);
        $curl->exec();

        $curl->setOpt(CURLOPT_ENCODING , 'utf-8');
        $html = $curl->post('https://ww1.sunat.gob.pe/ol-ti-itreciboelectronico/cpelec001Alias', [
            'accion' => 'descargaConsultaEmisor'
        ]);

        return iterator_to_array($this->parseTxt($html));
    }

    /**
     * Get xml content.
     *
     * @param int $pos Posicion de la busqueda
     * @return string|null
     */
    public function getRrhhXml($pos)
    {
        $curl = $this->req->getCurl();
        $curl->post('https://ww1.sunat.gob.pe/ol-ti-itreciboelectronico/cpelec001Alias', [
            'posirecibo' => $pos,
            'accion' => 'CapturaCriterioBusqueda2',
        ]);

        $curl->setOpt(CURLOPT_ENCODING, '');
        $curl->post('https://ww1.sunat.gob.pe/ol-ti-itreciboelectronico/cpelec001Alias', [
            'accion' => 'descargarreciboxml',
        ]);

        return $curl->rawResponse;
    }

    public function navigate(array $urls)
    {
        $curl = $this->req->getCurl();


        foreach ($urls as $url) {

            $curl->get($url);

            if($curl->error) {
                return false;
            }

            $headers = $curl->responseHeaders;

            if (isset($headers['Location'])) {
                $curl->get($headers['Location']);
            }
        }

        return true;
    }

    private function parseTxt($txt)
    {
        $separator = "\r\n";
        strtok($txt, $separator);
        $line = strtok($separator);
        while ($line !== false) {
            $items = explode('|', $line);
            $rrhh = new RrhhResult();
            $rrhh->fecEmision = $items[0];
            $rrhh->tipoDoc = $items[1];
            $rrhh->serieNroDoc = $items[2];
            $rrhh->estado = $items[3];
            $rrhh->clientTipoDoc = $items[4];
            $rrhh->clientNroDoc = rtrim($items[5]);
            $rrhh->clientRzSocial = $items[6];
            $rrhh->tipoRenta = $items[7];
            $rrhh->isGratuito = $items[8] == 'SI';
            $rrhh->descripcion = $items[9];
            $rrhh->observacion = $items[10];
            $rrhh->moneda = $items[11];
            $rrhh->rentaBruta = floatval($items[12]);
            $rrhh->impuestoRenta = floatval($items[13]);
            $rrhh->rentaNeta = floatval($items[14]);
            $rrhh->montoNetoPago = floatval($items[15]);

            yield $rrhh;
            $line = strtok($separator);
        }
    }

    /**
     * @param $html
     * @return mixed
     */
    private function getList($html)
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $nodes = $doc->getElementsByTagName('textarea');
        $text = $nodes->item(0)->textContent;

        $root = json_decode($text);
        if ($root->codeError != 0) {
            return [];
        }

        $objs = json_decode($root->data);


        return $objs;
    }

    /**
     * @param string $ruc
     * @param string $serie
     * @param string $correlativo
     * @return false|string El contenido del xml del comprabante electrónico.
     */
    public function getXmlFac($ruc, $serie, $correlativo)
    {
        $curl = $this->req->getCurl();
        $curl->post(self::URL_DOWNLOAD_XML_FAC, [
            'action' => 'descargarFactura',
            'ruc' => $ruc,
            'tipo' => '10',
            'serie' => $serie,
            'numero' => $correlativo,
        ]);

        if ($this->isInvalidFileResult($curl->responseHeaders)) {
            return false;
        }

        $reader = new ZipReader();
        $xml = $reader->decompressXmlFile($curl->rawResponse);

        return $xml;
    }

    /**
     * @param string $ruc
     * @param string $serie
     * @param string $correlativo
     * @return false|string El contenido del xml del comprabante electrónico.
     */
    public function getXmlBol($ruc, $serie, $correlativo)
    {
        $curl = $this->req->getCurl();
        $curl->post(self::URL_DOWNLOAD_XML_BOL, [
            'action' => 'descargarFactura',
            'ruc' => $ruc,
            'tipo' => '17',
            'serie' => $serie,
            'numero' => $correlativo,
        ]);

        if ($this->isInvalidFileResult($curl->responseHeaders)) {
            return false;
        }

        $reader = new ZipReader();
        $xml = $reader->decompressXmlFile($curl->rawResponse);

        return $xml;
    }

    /**
     * @param string $ruc
     * @param string $serie
     * @param string $correlativo
     * @return false|string El contenido del xml del comprabante electrónico.
     */
    public function getSeeXml($ruc, $serie, $correlativo)
    {
        $curl = $this->req->getCurl();
        $url = sprintf(self::URL_SEE_XML, $ruc, $serie, $correlativo);
        $fileZip = $curl->get($url);

        if ($this->isInvalidFileResult($curl->responseHeaders)) {
            return false;
        }

        $reader = new ZipReader();
        $xml = $reader->decompressXmlFile($fileZip);

        return $xml;
    }

    private function isInvalidFileResult($headers)
    {
        if (isset($headers['Content-Type']) &&
            strpos($headers['Content-Type'], 'text/html') !== false) {
            return true;
        }

        return false;
    }
}