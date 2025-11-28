<?php

/**
 * Variantes ortográficas comunes del español
 * Mapea errores de escritura, faltas de tildes y variantes informales
 * a sus traducciones correctas en Quechua
 */

return [
    // ========== PALABRAS SIN TILDES ==========
    'sin_tildes' => [
        // Saludos
        'hola' => 'Allin p\'unchay',
        'adios' => 'Tupananchiskama',
        'gracias' => 'Sulpayki',
        'perdon' => 'Pampachaway',
        'si' => 'Arí',
        'mas' => 'astawan',
        'despues' => 'qhipaman',
        'tambien' => 'ima hinan',
        
        // Preguntas comunes
        'como estas' => 'Imaynallan kashanki',
        'donde' => 'maypi',
        'cuando' => 'hayk\'aq',
        'cuanto' => 'hayk\'a',
        'quien' => 'pi',
        'que' => 'ima',
        'por que' => 'imaraykuchus',
        'cual' => 'mayqin',
        
        // Verbos comunes
        'esta' => 'kashan',
        'estas' => 'kashanki',
        'estoy' => 'kani',
        'sera' => 'kanqa',
        'podria' => 'atikuyman',
        'tendre' => 'kapusaq',
        'hare' => 'ruwasaq',
        'dire' => 'nisaq',
        'ire' => 'risaq',
        'vendre' => 'hamusaq',
        
        // Expresiones de trueque
        'cambio' => 'tikray',
        'intercambio' => 'khuskachiy',
        'necesito' => 'necesitani',
        'quiero' => 'munani',
        'busco' => 'maskani',
        'ofrezco' => 'quni',
        'acepto' => 'chaskini',
        'rapido' => 'utqhaylla',
        'facil' => 'mana sasa',
        'dificil' => 'sasa',
        'util' => 'allin',
        'practico' => 'allin',
    ],
    
    // ========== ERRORES ORTOGRÁFICOS COMUNES ==========
    'errores_comunes' => [
        // H inicial (muda)
        'ola' => 'Allin p\'unchay',
        'asta' => 'hayk\'akama',
        'ora' => 'hora',
        'acer' => 'ruway',
        'ay' => 'kan',
        'aver' => 'rikunapaq',
        'ahora' => 'kunan',
        'aora' => 'kunan',
        
        // B/V
        'berdad' => 'cheqa',
        'aber' => 'rikunapaq',
        'saver' => 'yachay',
        'tubo' => 'karqan',
        'estubo' => 'karqan',
        'benir' => 'hamuy',
        'bengo' => 'hamuni',
        'bale' => 'chanin',
        'bacaciones' => 'samay pacha',
        
        // LL/Y
        'lla' => 'ña',
        'yegar' => 'chayamuy',
        'yegue' => 'chayamuni',
        'yevar' => 'apay',
        'yevo' => 'apani',
        'ayuda' => 'yanapay',
        'ayer' => 'qayna',
        
        // S/C/Z
        'haser' => 'ruway',
        'conoser' => 'riqsiy',
        'nesesito' => 'necesitani',
        'nesesitas' => 'necesitanki',
        'empesar' => 'qallariy',
        'empiezo' => 'qallarini',
        'enpesar' => 'qallariy',
        'empieso' => 'qallarini',
        'servicio' => 'servicio',
        'serbisio' => 'servicio',
        'presio' => 'chanin',
        'precio' => 'chanin',
        'grasia' => 'sulpayki',
        'grasias' => 'sulpayki',
        
        // G/J
        'jente' => 'runakuna',
        'trajiste' => 'apamurqanki',
        'dijiste' => 'nirqanki',
        'trabajar' => 'llamk\'ay',
        'trabahar' => 'llamk\'ay',
        'viahe' => 'viaje',
        
        // Duplicaciones incorrectas
        'aver' => 'rikunapaq',
        'aber' => 'rikunapaq',
    ],
    
    // ========== FRASES SIN TILDES ==========
    'frases_sin_tildes' => [
        // Preguntas
        'como te llamas' => 'Imataq sutiyki',
        'donde vives' => 'Maypichus tiyakunki',
        'cuanto cuesta' => 'Hayk\'ataq chanin',
        'cuando nos vemos' => 'Hayk\'aq rikunakusun',
        'que haces' => 'Imataq ruwashanki',
        'que tal' => 'Imaynalla',
        'como va' => 'Imaynalla',
        'de donde eres' => 'Maymantachus kanki',
        
        // Confirmaciones
        'esta bien' => 'Allinmi',
        'si claro' => 'Arí seguro',
        'por supuesto' => 'Arí riki',
        'como no' => 'Allinmi',
        'esta confirmado' => 'Confirmasqañam',
        
        // Negaciones
        'no se' => 'Manan yachanichu',
        'no puedo' => 'Manan atinichu',
        'no tengo' => 'Manan kapuwanichu',
        'no entiendo' => 'Manan hamut\'anichu',
        'no me interesa' => 'Manan interesakuwanichu',
        
        // Tiempo
        'que hora es' => 'Hayk\'a hora',
        'a que hora' => 'Hayk\'a horamantataq',
        'manana temprano' => 'Paqarin temprano',
        'hoy dia' => 'Kunan p\'unchay',
        'el otro dia' => 'Huk p\'unchay',
        
        // Ubicación
        'donde quedamos' => 'Maypichus tupanakusun',
        'en que lugar' => 'May chaypichus',
        'como llego alli' => 'Imaynatataq chayamuni',
        
        // Cortesía
        'muchas gracias' => 'Ancha sulpayki',
        'de nada' => 'Manataqmi',
        'por favor' => 'Allichu',
        'con permiso' => 'Permisolla',
        'disculpa' => 'Pampachaway',
        
        // Trueques
        'te sirve esto' => 'Kayqa allinchu kanman',
        'me sirve' => 'Allinmi',
        'no me sirve' => 'Manan allinchu',
        'cuanto me das' => 'Hayk\'ataq qowanki',
        'que me ofreces' => 'Imataq qunki',
        'te acepto' => 'Chaskiyki',
        'esta bien asi' => 'Allinmi chayna',
        'me parece bien' => 'Allin qhawarini',
    ],
    
    // ========== FRASES CON ERRORES COMUNES ==========
    'frases_con_errores' => [
        // H inicial
        'ola que tal' => 'Allin p\'unchay, imaynalla',
        'ola como estas' => 'Allin p\'unchay, imaynallan kashanki',
        'asta luego' => 'Tupananchiskama',
        'asta manana' => 'Paqarinkama',
        'aora mismo' => 'Kunan kikinpi',
        'ace frio' => 'Chiriyachkan',
        'ace calor' => 'Ruphayachkan',
        
        // B/V
        'bale la pena' => 'Allinmi',
        'no bale' => 'Manan allinchu',
        'bengo ahorita' => 'Hamusaq kunalla',
        'tube que' => 'Karqan',
        'estubo bien' => 'Allin karqan',
        
        // Nesesito
        'nesesito ayuda' => 'Yanapay necesitani',
        'nesesito esto urgente' => 'Urgente kayta necesitani',
        'nesesitas algo' => 'Ima necesitankichu',
        'lo nesesito ya' => 'Kunañam necesitani',
        
        // Quiero/kiero
        'kiero aprender' => 'Yachakunayta munani',
        'kiero saber' => 'Yachayta munani',
        'kiero ir' => 'Riyta munani',
        'kiero cambiar' => 'Tikrayta munani',
        
        // Hacer/acer
        'puedes acer esto' => 'Kayta ruwayta atinkichu',
        'como lo ago' => 'Imaynatataq ruway',
        'yo lo ago' => 'Ñuqa ruway',
        'quien lo ase' => 'Pitaq ruwanqa',
        
        // Estar/tar
        'toy aqui' => 'Kaypi kani',
        'toy llegando' => 'Chayamuchkani',
        'tas alli' => 'Chaypichu kashanki',
        'ta bien' => 'Allinmi',
        'tamos de acuerdo' => 'Allinmi',
        
        // Para/pa
        'pa cuando' => 'Hayk\'apaqtaq',
        'pa donde' => 'Maypaqtaq',
        'pa mi' => 'Ñuqapaq',
        'pa ti' => 'Qampaq',
        'pa que' => 'Imapaqtaq',
        
        // Pero/pro
        'pro no se' => 'Ichaqa manan yachanichu',
        'pro si' => 'Ichaqa arí',
        
        // Después/despues/dspues
        'dspues' => 'qhipaman',
        'dsps' => 'qhipaman',
        'd1' => 'qhipaman',
        
        // También/tambien/tmb
        'tmb yo' => 'Ñuqapas',
        'tmb quiero' => 'Ñuqapas munani',
        'yo tmb' => 'Ñuqapas',
        
        // Porque/xq/xk/pq/pk
        'xq' => 'imaraykuchus',
        'xk' => 'imaraykuchus',
        'pq' => 'imaraykuchus',
        'pk' => 'imaraykuchus',
        'x q' => 'imaraykuchus',
        'p q' => 'imaraykuchus',
        'por q' => 'imaraykuchus',
        'xq no' => 'imaraykuchus mana',
        
        // Que/q/k
        'q es eso' => 'Imataq chay',
        'q haces' => 'Imataq ruwashanki',
        'k onda' => 'Imaynalla',
        'k tal' => 'Imaynalla',
        'k hay' => 'Imataq kan',
        
        // Contigo/kontigo/ctgo
        'kontigo' => 'qanwan',
        'ctgo' => 'qanwan',
        
        // Conmigo/konmigo/cmgo
        'konmigo' => 'ñuqawan',
        'cmgo' => 'ñuqawan',
    ],
    
    // ========== ABREVIACIONES Y LENGUAJE INFORMAL ==========
    'abreviaciones' => [
        // Abreviaciones comunes
        'bn' => 'allin',
        'bno' => 'allin',
        'bb' => 'wawa',
        'bbs' => 'wawakuna',
        'tqm' => 'ancha kuyakuyki',
        'tkm' => 'ancha kuyakuyki',
        'ok' => 'allinmi',
        'okey' => 'allinmi',
        'we' => 'masi',
        'bro' => 'wawqi',
        'sis' => 'panay',
        'compa' => 'masi',
        'pe' => 'riki',
        'ps' => 'riki',
        'pues' => 'riki',
        'ño' => 'mana',
        'sep' => 'arí',
        'sip' => 'arí',
        'nop' => 'mana',
        
        // Números
        '1' => 'huk',
        '2' => 'iskay',
        '3' => 'kimsa',
        '4' => 'tawa',
        '5' => 'pisqa',
        '6' => 'suqta',
        '7' => 'qanchis',
        '8' => 'pusaq',
        '9' => 'isqun',
        '10' => 'chunka',
        
        // Tiempo abreviado
        'tb' => 'ima hinan',
        'tbn' => 'ima hinan',
        'tbm' => 'ima hinan',
        'hoy' => 'kunan p\'unchay',
        'ayer' => 'qayna',
        'mñn' => 'paqarin',
        'mañ' => 'paqarin',
        'mñna' => 'paqarin',
        'ahora' => 'kunan',
        'ahorita' => 'kunalla',
        'aora' => 'kunan',
        'orita' => 'kunalla',
        'ya' => 'ña',
        'tarde' => 'chisi',
        'temprano' => 'paqarin',
        
        // Respuestas rápidas
        'si' => 'arí',
        'no' => 'mana',
        'ns' => 'manan yachanichu',
        'np' => 'manan problemayuqchu',
        'nd' => 'imapas',
        'ntc' => 'manataqmi',
        'dk' => 'manan yachanichu',
        
        // Expresiones
        'xfa' => 'allichu',
        'xfavor' => 'allichu',
        'porfis' => 'allichu',
        'porfa' => 'allichu',
        'grax' => 'sulpayki',
        'grcs' => 'sulpayki',
        'thx' => 'sulpayki',
        'ty' => 'sulpayki',
        'salu2' => 'Allin p\'unchay',
        'saludos' => 'Allin p\'unchay',
        'besitos' => 'much\'aykuna',
        'bsos' => 'much\'aykuna',
        
        // Consultas rápidas
        'dnd' => 'maypi',
        'cnd' => 'hayk\'aq',
        'cnt' => 'hayk\'a',
        'cm' => 'imayna',
        'xa' => 'para',
        'xo' => 'pero',
    ],
    
    // ========== VARIANTES REGIONALES PERUANAS ==========
    'regionalismos' => [
        // Jerga peruana común
        'causa' => 'masi',
        'pe' => 'riki',
        'pues' => 'riki',
        'nomás' => 'sapalla',
        'nomas' => 'sapalla',
        'ya pues' => 'ña riki',
        'ya pe' => 'ña riki',
        'bien bien' => 'ancha allin',
        'full' => 'hunt\'a',
        'too' => 'tukuy',
        'tono' => 'tukuy',
        'todito' => 'tukuynin',
        'altoke' => 'utqhaylla',
        'al toque' => 'utqhaylla',
        'de una' => 'utqhaylla',
        'jato' => 'wasi',
        'chamba' => 'llamk\'ay',
        'chambar' => 'llamk\'ay',
        'jama' => 'mikhuna',
        'jamar' => 'mikhuy',
        'jato' => 'wasi',
        'floro' => 'parlay',
        'gil' => 'upalla',
        'piña' => 'phiña',
        'arrecho' => 'phiña',
        'bacán' => 'sumaq',
        'paja' => 'llulla',
        'tranca' => 'sasa',
        'mosca' => 'qhaway',
    ],
];
