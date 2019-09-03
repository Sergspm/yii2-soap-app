<?php

/* @var string $host */

?>
<definitions name = "HelloService"
             targetNamespace = "http://www.examples.com/wsdl/HelloService.wsdl"
             xmlns = "http://schemas.xmlsoap.org/wsdl/"
             xmlns:soap = "http://schemas.xmlsoap.org/wsdl/soap/"
             xmlns:tns = "http://www.examples.com/wsdl/HelloService.wsdl"
             xmlns:xsd = "http://www.w3.org/2001/XMLSchema">

    <message name="CalcRequest">
        <part name="city" type="xsd:string" />
        <part name="name" type="xsd:string" />
        <part name="date" type="xsd:string" />
        <part name="persons" type="xsd:string" />
        <part name="bed_count" type="xsd:string" />
        <part name="has_child" type="xsd:string" />
    </message>

    <message name="CalcResponse">
        <part name="price" type="xsd:string"/>
        <part name="info" type="xsd:string"/>
        <part name="error" type="xsd:string"/>
    </message>

    <portType name="CalcPortType">
        <operation name="calculate">
            <input message="tns:CalcRequest"/>
            <output message="tns:CalcResponse"/>
        </operation>
    </portType>

    <binding name="CalcBinding" type="tns:CalcPortType">
        <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>
        <operation name="calculate">
            <soap:operation soapAction="calculate"/>
            <input>
            <soap:body
                    encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
                    namespace="urn:soap:calculate"
                    use="encoded"/>
            </input>

            <output>
                <soap:body
                        encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
                        namespace="urn:soap:calculate"
                        use="encoded"/>
            </output>
        </operation>
    </binding>

    <service name="CalculatorForm">
        <documentation>WSDL File for CalcService</documentation>
        <port binding="tns:CalcBinding" name="CalcPort">
            <soap:address location="http://<?= $host ?>/calculate-soap" />
        </port>
    </service>
</definitions>
