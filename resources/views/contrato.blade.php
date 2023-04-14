<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Contrato Prestacao Servicos - CT - {{$contrato->id}}</title>
    <style>

        /* Estilos CSS aqui */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h3 {
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td, th {

        }

        .terms {
            margin-top: 30px;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .signature p {
            margin-bottom: 10px;
        }

        .even {
            background-color: #f6f6f6;
        }

        .odd {
            background-color: #ffffff;
        }
        p {
            text-align: justify;
        }
    </style>
</head>
<body>
<div style="display: flex; align-items: center; justify-content: space-between; ">
    <div>
        <img src="https://www.alemidia.com.br/wa_images/logo.png" width="150px">
        <div style="display: inline-block; float: right; border: 1px solid black; padding: 5px; font-weight: bold; color: red;">CT - {{ $contrato->id }}
    </div>
</div>

<div style="text-align: center; margin-top: 15px;">
    <div style="display: inline-block; font-size: 1.17em; font-weight: bold">CONTRATO DE PRESTAÇÃO DE SERVIÇOS</div>

    </div>
</div>


<h4>1.CONTRATANTE:</h4>

    <table>
        @if( $contrato->Cliente->tipo_cadastro === 'pf')
        <tr>
            <td style="width: 18px">Nome:</td>
            <td style="border-bottom: 1px solid #000 ; width: 300px ; font-weight: bold">{{ $contrato->Cliente->nome_razao_social }}</td>
            <td style="width: 18px">RG:</td>
            <td style="border-bottom: 1px solid #000 ; width: 100px ; font-weight: bold">{{ $contrato->Cliente->rg_ie }}</td>
            <td style="width: 18px">CPF:</td>
            <td style="border-bottom: 1px solid #000; width: 100px ; font-weight: bold">{{ preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $contrato->Cliente->cpf_cnpj) }}</td>
        </tr>
        @else
            <tr>
                <td style="">Razão Social:</td>
                <td style="border-bottom: 1px solid #000 ; width: 340px ; font-weight: bold">{{ $contrato->Cliente->nome_razao_social }}</td>
                <td style="width: 18px">IE:</td>
                <td style="border-bottom: 1px solid #000 ; width: 100px ; font-weight: bold">{{ $contrato->Cliente->rg_ie }}</td>
                <td style="width: 18px">CNPJ:</td>
                <td style="border-bottom: 1px solid #000; width: 120px ; font-weight: bold">{{ preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $contrato->Cliente->cpf_cnpj) }}</td>
            </tr>
        @endif
    </table>
    <table>
        <tr>
            <td style="width: 130px">residente e domiciliado à</td>
            <td style="border-bottom: 1px solid #000 ; width: 350px ; font-weight: bold">{{ $contrato->Cliente->logradouro }},
                {{$contrato->Cliente->numero}}</td>
            <td style="width: 18px">Complemento:</td>
            <td style="border-bottom: 1px solid #000 ; width: 100px ; font-weight: bold">{{ $contrato->Cliente->complemento }}</td>

        </tr>
    </table>
    <table>
        <tr>
            <td style="width: 15px">Cidade:</td>
            <td style="border-bottom: 1px solid #000 ; width: 150px ; font-weight: bold">{{ $contrato->Cliente->cidade }}</td>
            <td style="width: 25px">UF:</td>
            <td style="border-bottom: 1px solid #000 ; width: 30px ; font-weight: bold">{{ $contrato->Cliente->estado }}</td>
            <td style="width: 25px">CEP:</td>
            <td style="border-bottom: 1px solid #000 ; width: 70px ; font-weight: bold">{{ preg_replace('/^(\d{5})(\d{3})$/', '$1-$2', $contrato->Cliente->cep) }}</td>
            <td style="">ora denominada como Contratante ou ainda quando em partes</td>

        </tr>
    </table>
    <table>
        <tr>
            <td style="width: 30px">Celular:</td>
            <td style="border-bottom: 1px solid #000 ; width: 100px ; font-weight: bold">{{ preg_replace('/^\(?(\d{2})\)?\s?(\d{4,5})-?(\d{4})$/', '($1) $2-$3', $contrato->Cliente->celular )}}</td>
            <td style="width: 60px">Celular 2:</td>
            <td style="border-bottom: 1px solid #000 ; width: 100px ; font-weight: bold">{{ preg_replace('/^\(?(\d{2})\)?\s?(\d{4,5})-?(\d{4})$/', '($1) $2-$3', $contrato->Cliente->celular2) }}</td>
            <td style="width: 40px">E-mail</td>
            <td style="border-bottom: 1px solid #000 ; ; font-weight: bold">{{ $contrato->Cliente->mail }}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="width: 30px">Assessoria:</td>
            <td style="border-bottom: 1px solid #000 ; width: 350px ; font-weight: bold">{{ $contrato->Assessoria->nome_razao_social }}</td>
            <td style="width: 30px">Contato:</td>
            <td style="border-bottom: 1px solid #000 ; width: 100px ; font-weight: bold">{{ preg_replace('/^\(?(\d{2})\)?\s?(\d{4,5})-?(\d{4})$/', '($1) $2-$3', $contrato->Assessoria->celular) }}</td>
        </tr>
    </table>
        <h3 style="
         margin-right: auto;
         margin-left: auto;
         background-color: #cbd5e0;
         width: 250px ;
         border-bottom: 1px solid #000;
         text-align: center;
         font-weight: bold;
         font-size: 16px">
            {{ strtoupper($contrato->noivo_debutante) }}
        </h3>

    <h4>2.CONTRATADA:</h4>

    <p> {{$dadosEmpresa->razao_social}}, portadora do CNPJ: {{ preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5',$dadosEmpresa->cnpj) }}, representada por {{ $dadosEmpresa->representante_legal }}
        portador do CPF: {{  preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4',$dadosEmpresa->cpf_representante) }} e RG: {{ $dadosEmpresa->rg_representante }} estabelecida à
        {{ $dadosEmpresa->logradouro }},  {{ $dadosEmpresa->numero }} , {{ $dadosEmpresa->cidade }}, {{ $dadosEmpresa->estado }}, CEP: {{ preg_replace('/^(\d{5})(\d{3})$/', '$1-$2',$dadosEmpresa->cep)  }}
        ora denominado como Contratada ou ainda quando em conjunto como partes.
    </p>

    <h4>3.DO OBJETO:</h4>

    <p> Cláusula 1ª - O Presente contrato tem como objeto a prestação de serviço de locação de equipamentos de efeitos especias, a ser realizado no dia: </p>

    <h3 style="
         margin-right: auto;
         margin-left: auto;
         background-color: #cbd5e0;
         width: 250px ;
         border-bottom: 1px solid #000;
         text-align: center;
         font-weight: bold;
         font-size: 16px">
        {{ \Carbon\Carbon::createFromFormat('Y-m-d', $contrato->data_evento)->format('d/m/Y') }}
    </h3>

    <table>
        <tr>
            <td style="width: 60px">No espaço:</td>
            <td style="border-bottom: 1px solid #000 ; width: 340px ; font-weight: bold">{{ $contrato->Buffet->nome_razao_social }}</td>
            <td style="width: 100px">cujo horario será das </td>
            <td style="border-bottom: 1px solid #000 ; width: 30px ; font-weight: bold">{{ \Carbon\Carbon::createFromTimeString($contrato->hora_inicio)->format('H:i') }}</td>
            <td style="width: 20px"> as </td>
            <td style="border-bottom: 1px solid #000 ; width: 30px ; font-weight: bold">{{ \Carbon\Carbon::createFromTimeString($contrato->hora_fim)->format('H:i')  }}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="width: 80px">localizado à :</td>
            <td style="border-bottom: 1px solid #000 ;  ; font-weight: bold">
                {{ $contrato->Buffet->logradouro }},
                {{ $contrato->Buffet->numero }} -
                {{ $contrato->Buffet->bairro }} -
                {{ $contrato->Buffet->cidade }}/
                {{ $contrato->Buffet->estado }} - CEP:
                {{ preg_replace('/^(\d{5})(\d{3})$/', '$1-$2',$contrato->Buffet->cep) }}
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="">com aproximadamente:</td>
            <td style="border-bottom: 1px solid #000 ; ; font-weight: bold">{{ $contrato->qtde_convidados }}</td>
            <td style="">convidados. </td>
            <td style="width: 480px"></td>
        </tr>
    </table>

    <p>Produtos:</p>

    @foreach($contrato->ContratoProduto as $produto)
        @if($produto->Produto->slug == 'totem' || $produto->Produto->slug == 'cabine')
            <p>• @if($produto->quantidade <10) 0{{$produto->quantidade}} @else{{$produto->quantidade}}@endif
             .({{ (new  phputil\extenso\Extenso)->extenso($produto->quantidade, \phputil\extenso\Extenso::NUMERO_MASCULINO) }}) - {{ $produto->Produto->nome }} com
                @if($produto->quantidade_foto === 0) <strong>ILIMITADAS fotos impressas</strong> @else <strong>{{ $produto->quantidade_foto }} fotos impressas </strong> @endif
                com o tipo de impressao
                @if($produto->impressao === "definir") <strong>a definir</strong>
                @elseif ($produto->impressao === "1_pose")<strong>1 pose</strong>
                @elseif ($produto->impressao === "4_poses") <strong>4 poses</strong>
                @elseif ($produto->impressao === "tirinha") <strong>tirinha</strong>
                @endif
                pelo tempo de
                <strong>{{ $produto->tempo_evento }} hora(s)</strong> durante o evento;
            </p>
            @elseif($produto->Produto->slug == 'espelho_magico')
            <p>•@if($produto->quantidade <10) 0{{$produto->quantidade}} @else{{$produto->quantidade}}@endif
                .({{ (new  phputil\extenso\Extenso)->extenso($produto->quantidade, \phputil\extenso\Extenso::NUMERO_MASCULINO) }}) - {{ $produto->Produto->nome }} com
                @if($produto->quantidade_foto === 0) <strong>ILIMITADAS fotos impressas</strong> @else <strong>{{ $produto->quantidade_foto }} fotos impressas </strong> @endif
                pelo tempo de
                <strong>{{ $produto->tempo_evento }} hora(s)</strong> durante o evento;
            </p>

        @elseif($produto->Produto->slug == 'robo_led')
            <p>• @if($produto->quantidade <10) 0{{$produto->quantidade}} @else{{$produto->quantidade}}@endif
                .({{ (new  phputil\extenso\Extenso)->extenso($produto->quantidade, \phputil\extenso\Extenso::NUMERO_MASCULINO) }}) - {{ $produto->Produto->nome }} com
                @if($produto->bazuca)  com Bazuca de CO² @endif
                pelo tempo de
                <strong>{{ $produto->tempo_evento }} hora(s)</strong> durante o evento;
            </p>

        @else
            <p>• @if($produto->quantidade <10) 0{{$produto->quantidade}} @else{{$produto->quantidade}}@endif
                .({{ (new  phputil\extenso\Extenso)->extenso($produto->quantidade, \phputil\extenso\Extenso::NUMERO_MASCULINO) }}) - {{ $produto->Produto->nome }} pelo tempo de
                <strong>{{ $produto->tempo_evento }} hora(s)</strong> durante o evento;
            </p>
        @endif
    @endforeach

    <table>
        <tr>
            <td style="width: 70px">Observações:</td>
            <td style="border-bottom: 1px solid #000 ; ; font-weight: bold">{{ $contrato->observacoes }}</td>
{{--            <td style="width: 480px"></td>--}}
        </tr>
    </table>


<h4>4.OBRIGAÇÕES</h4>
    <p>Cláusula 2ª - A Contratada ora se obriga a prestar serviço, conforme prevista abaixo:</p>

    @foreach($contrato->ContratoProduto as $produto)
        <p> • @if($produto->quantidade <10) 0{{$produto->quantidade}} @else{{$produto->quantidade}}@endif
            .({{ (new  phputil\extenso\Extenso)->extenso($produto->quantidade, \phputil\extenso\Extenso::NUMERO_MASCULINO) }}) - {{ $produto->Produto->descricao }}</p>


    @endforeach


    <p>Cláusula 3ª - Em caso de desistência por parte da Contratada por qualquer razão, a mesma devolverá 100% da quantia paga pelo contratante.</p>
    <p>Cláusula 4ª - A Contratante se obriga a disponibilizar todo e qualquer material para personalização dos itens (fotos, vídeos, informações, etc.) com até 10 (dez) dias de antecedência ao
        evento. Acordar com a equipe aonde deverão ser posicionados os itens contratados, disponibilizar espaço e acomodação necessária para prestação do serviço (incluindo mesa e
        cadeira para montagem de House VJ, no caso de cabine) e disponibilizar energia elétrica (informar voltagem).</p>
    <p>Cláusula 5ª - Será de total e inteira responsabilidade do Contratante qualquer dano físico causado ao equipamento na Cláusula 2ª, causado por ele mesmo, convidados ou demais
        contratadas no evento.</p>

    <h4>5.PAGAMENTO</h4>

        @if($contrato->CondicaoPagamento->slug === 'permuta' || $contrato->CondicaoPagamento->slug === 'cortesia' ||
            $contrato->CondicaoPagamento->slug === 'contrato_fake' )
            <p> Cláusula 6ª A Contratante pagará o contrato da seguinte forma: {{ $contrato->CondicaoPagamento->nome }} </p>
            <table>
                <tr>
                    <td style="width: 70px">Observações:</td>
                    <td style="border-bottom: 1px solid #000 ; ; font-weight: bold">{{ $contrato->observacao_pagamento }}</td>
                    {{--            <td style="width: 480px"></td>--}}
                </tr>
            </table>
        @elseif($contrato->CondicaoPagamento->slug === 'a_vista')
            <p> Cláusula 6ª A Contratante pagará o contrato da seguinte forma: <strong>{{ (new \NumberFormatter('pt_BR', \NumberFormatter::CURRENCY))->formatCurrency($contrato->valor_total, 'BRL')  }}
                    ({{ (new  phputil\extenso\Extenso)->extenso($contrato->valor_total, \phputil\extenso\Extenso::MOEDA) }})</strong> sendo o pagamento <strong>á vista</strong> com data para dia
                @foreach ($contrato->ContratoPagamento as $pagamento) <strong>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $pagamento->data_pagamento)->format('d/m/Y') }}</strong> que deverá ser realizado através
                de <strong>{{$pagamento->FormaPagamento->descricao}}</strong>@endforeach
            </p>
        @elseif($contrato->CondicaoPagamento->slug === 'parcelado')
            <p> Cláusula 6ª A Contratante pagará o contrato da seguinte forma: <strong>{{ (new \NumberFormatter('pt_BR', \NumberFormatter::CURRENCY))->formatCurrency($contrato->valor_total, 'BRL')  }}
                    ({{ (new  phputil\extenso\Extenso)->extenso($contrato->valor_total, \phputil\extenso\Extenso::MOEDA) }})</strong> sendo o valor total <strong>parcelado</strong>  em
                <strong> {{$contrato->parcelas}} ({{ (new  phputil\extenso\Extenso)->extenso($contrato->parcelas, \phputil\extenso\Extenso::NUMERO_MASCULINO) }})</strong>
               parcelas com o vencimento a seguir:
            </p>
                    <table style="border-collapse: collapse; width: 100%; max-width: 600px; margin: 0 auto;">
                        <thead>
                        <tr style="background-color: #f2f2f2;">
                            <th style="text-align: left; padding: 8px;">Data Pagamento</th>
                            <th style="text-align: left; padding: 8px;">Forma Pagamento</th>
                            <th style="text-align: left; padding: 8px;">Valor Parcela</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($contrato->ContratoPagamento as $pagamento)
                        <tr class="{{ $loop->iteration % 2 == 0 ? 'even' : 'odd' }}">
                            <td style="padding: 8px;">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $pagamento->data_pagamento)->format('d/m/Y') }}</td>
                            <td style="padding: 8px;">{{ $pagamento->FormaPagamento->descricao }}</td>
                            <td style="padding: 8px;">{{ (new \NumberFormatter('pt_BR', \NumberFormatter::CURRENCY))->formatCurrency($pagamento->valor, 'BRL')  }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>


        @endif

    <h4>6.RESCISÃO CONTRATUAL</h4>
    <p> Cláusula 7ª - Caso a Contratante deseje rescindir o presente Contrato, a mesma deverá notificar de forma escrita, mediante qualquer comprovante de que ambas as partes estão de
        fato ciente da desistência, com antecedência mínima de 60 (sessenta) dias antes do evento, sendo que a Contratante não terá direito ao reembolso após esse período.</p>
    <p>
        Parágrafo único - O reembolso será no percentual de 10% (Dez por cento) do valor das parcelas pagas pela Contratante, senddo o restante utilizado para quitar os demais custos que a
        Contratada já tenha tido.
    </p>
    <p>
        Cláusula 8ª - Estará rescindido automaticamente o presente Contrato, em ocorrendo a violação de qualquer Cláusula, seja por dolo ou culpa, cabendo a parte que deu causa a violação
        a multa equivalente a 30% (trinta por cento) que deverá ser paga no dia seguinte a prática do ato que violou o presente contrato dando causa a sua rescisão
    </p>

    <h4>7.VALIDADE DO PRAZO DO CONTRATO:</h4>
    <p>
        Cláusula 9ª - Qualquer concessão feita pela Contratada em favor da Contratante será mera liberdade da Contratada, não incidindo em revogação, aceitação ou alteração tática das
        cláusulas do presente Contrato.
    </p>
    <p>
        Cláusula 10ª - O presemte Contrato passa a vigorar na data em que as partes o tiverem assinado e vigorará até a data do evento.
    </p>

    <h4>8.DO FORO:</h4>
    <p>
        Cláusula 11ª - Para dirimir quaisquer controvérsias oriundas do presene Contrato, as partes elegem o foro central da comarca de Jundiaí do Estado de São Paulo. Por estaram assim
        tidas as partes, justas e Contratadas, firmam o presente Contrato, em 03 (três) vias originias e de igual teor. Autorizo a publicação de imagens via internet e redes sociais.
    </p>

    <h3 style=" text-align: right">
        {{ $dadosEmpresa->cidade }}/{{$dadosEmpresa->estado}},
        {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$contrato->updated_at)->day}} de
        {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$contrato->updated_at)->monthName}} de
        {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$contrato->updated_at)->year}}.
    </h3>

    <div style="margin-top: 90px;">
        <div style="float: left; width: 50%; height: 20px; border-bottom: 1px solid black;"></div>
        <div style="float: left; width: 50%; height: 20px; border-bottom: 1px solid black; margin-left: 10px;"></div>
        <div style="clear: both;"></div>
        <div style="float: left; width: 50%; text-align: center; margin-top: 5px; font-weight: bold">{{ strtoupper($contrato->Cliente->nome_razao_social)  }}</div>
        <div style="float: left; width: 50%; text-align: center; margin-top: 5px; font-weight: bold ; margin-left: 10px;">{{ strtoupper($dadosEmpresa->razao_social) }}</div>
        <div style="clear: both;"></div>
    </div>


















{{--<div style="position: fixed; bottom: 0; left: 0; right: 0; text-align: center;">--}}
{{--    Página {{$page}} de {PAGE_COUNT}--}}
{{--</div>--}}

</body>
</html>






