let $LITTLE = $('#little');
let $MEDIUM = $('#medium');
let $BIG = $('#big');
let $EXTREME = $('#extreme');
let $LITTLE_COLUMN = $('.collect-little');
let $MEDIUM_COLUMN = $('.collect-medium');
let $BIG_COLUMN = $('.collect-big');
let $EXTREME_COLUMN = $('.collect-extreme');
let $LANCER = $('#lancer');
let $SWORDSMAN = $('#swordsman');
let $BARBARIAN = $('#barbarian');
let $ARCHER = $('#archer');
let $LIGHT_CAVALRY = $('#light-cavalry');
let $ARCHER_HORSEBACK = $('#archer-horseback');
let $HEAVY_CAVALRY = $('#heavy-cavalry');
let operationWeight = 0;

$(document).ready(function(){
    enableTable();
});

function enableTable(){
    verifyColumn($LITTLE);
    verifyColumn($MEDIUM);
    verifyColumn($BIG);
    verifyColumn($EXTREME);
}

function updateOperationWeight(){
    operationWeight = 0;
    if($LITTLE.prop('checked')){
        operationWeight += parseFloat($LITTLE.val());
    }
    if($MEDIUM.prop('checked')){
        operationWeight += parseFloat($MEDIUM.val());
    }
    if($BIG.prop('checked')){
        operationWeight += parseFloat($BIG.val());
    }
    if($EXTREME.prop('checked')){
        operationWeight += parseFloat($EXTREME.val());
    }
}

function verifyColumn($CHECKBOX){
    let $COLUMNS = $('.collect-'+$CHECKBOX.prop('id'));
    updateOperationWeight();
    if($CHECKBOX.prop('checked')){
        $COLUMNS.show();
        return;
    }
    $COLUMNS.hide();
}

function getInputValueFloat($INPUT){
    if($INPUT.val() === ''){
        return 0;
    }
    return parseFloat($INPUT.val());
}

function calcSingleCollect($UNITY_TYPE, $COLLECT_TYPE){
    return Math.round(((getInputValueFloat($COLLECT_TYPE)*getInputValueFloat($UNITY_TYPE))/operationWeight));
}

function calcCollect(){
    let isLittleChecked =  $LITTLE.prop('checked');
    let isMediumChecked =  $MEDIUM.prop('checked');
    let isBigChecked =  $BIG.prop('checked');
    let isExtremeChecked =  $EXTREME.prop('checked');
    if(isLittleChecked){
        // let resourcesLittle = 0;
        let qtdLancer = calcSingleCollect($LITTLE,$LANCER);
        let qtdSwordsman = calcSingleCollect($LITTLE,$SWORDSMAN);
        let qtdBarbarian = calcSingleCollect($LITTLE,$BARBARIAN);
        let qtdArcher = calcSingleCollect($LITTLE,$ARCHER);
        let qtdLightCavalry = calcSingleCollect($LITTLE,$LIGHT_CAVALRY);
        let qtdArcherHorseback = calcSingleCollect($LITTLE,$ARCHER_HORSEBACK);
        let qtdHeavyCavalry = calcSingleCollect($LITTLE,$HEAVY_CAVALRY);
        // resourcesLittle += (qtdLancer*25*0.1);
        // resourcesLittle += (qtdSwordsman*15*0.1);
        // resourcesLittle += (qtdBarbarian*10*0.1);
        // resourcesLittle += (qtdLightCavalry*80*0.1);
        // resourcesLittle += (qtdArcher*10*0.1);
        // resourcesLittle += (qtdArcherHorseback*10*0.1);
        // resourcesLittle += (qtdHeavyCavalry*50*0.1);
        $('#lancer-row .collect-little').text(qtdLancer);
        $('#swordsman-row .collect-little').text(qtdSwordsman);
        $('#barbarian-row .collect-little').text(qtdBarbarian);
        $('#archer-row .collect-little').text(qtdArcher);
        $('#light-cavalry-row .collect-little').text(qtdLightCavalry);
        $('#archer-horseback-row .collect-little').text(qtdArcherHorseback);
        $('#heavy-cavalry-row .collect-little').text(qtdHeavyCavalry);
    }
    if(isMediumChecked){
        let qtdLancer = calcSingleCollect($MEDIUM,$LANCER);
        let qtdSwordsman = calcSingleCollect($MEDIUM,$SWORDSMAN);
        let qtdBarbarian = calcSingleCollect($MEDIUM,$BARBARIAN);
        let qtdArcher = calcSingleCollect($MEDIUM,$ARCHER);
        let qtdLightCavalry = calcSingleCollect($MEDIUM,$LIGHT_CAVALRY);
        let qtdArcherHorseback = calcSingleCollect($MEDIUM,$ARCHER_HORSEBACK);
        let qtdHeavyCavalry = calcSingleCollect($MEDIUM,$HEAVY_CAVALRY);
        $('#lancer-row .collect-medium').text(qtdLancer);
        $('#swordsman-row .collect-medium').text(qtdSwordsman);
        $('#barbarian-row .collect-medium').text(qtdBarbarian);
        $('#archer-row .collect-medium').text(qtdArcher);
        $('#light-cavalry-row .collect-medium').text(qtdLightCavalry);
        $('#archer-horseback-row .collect-medium').text(qtdArcherHorseback);
        $('#heavy-cavalry-row .collect-medium').text(qtdHeavyCavalry);
    }
    if(isBigChecked){
        let qtdLancer = calcSingleCollect($BIG,$LANCER);
        let qtdSwordsman = calcSingleCollect($BIG,$SWORDSMAN);
        let qtdBarbarian = calcSingleCollect($BIG,$BARBARIAN);
        let qtdArcher = calcSingleCollect($BIG,$ARCHER);
        let qtdLightCavalry = calcSingleCollect($BIG,$LIGHT_CAVALRY);
        let qtdArcherHorseback = calcSingleCollect($BIG,$ARCHER_HORSEBACK);
        let qtdHeavyCavalry = calcSingleCollect($BIG,$HEAVY_CAVALRY);
        $('#lancer-row .collect-big').text(qtdLancer);
        $('#swordsman-row .collect-big').text(qtdSwordsman);
        $('#barbarian-row .collect-big').text(qtdBarbarian);
        $('#archer-row .collect-big').text(qtdArcher);
        $('#light-cavalry-row .collect-big').text(qtdLightCavalry);
        $('#archer-horseback-row .collect-big').text(qtdArcherHorseback);
        $('#heavy-cavalry-row .collect-big').text(qtdHeavyCavalry);
    }
    if(isExtremeChecked){
        let qtdLancer = calcSingleCollect($EXTREME,$LANCER);
        let qtdSwordsman = calcSingleCollect($EXTREME,$SWORDSMAN);
        let qtdBarbarian = calcSingleCollect($EXTREME,$BARBARIAN);
        let qtdArcher = calcSingleCollect($EXTREME,$ARCHER);
        let qtdLightCavalry = calcSingleCollect($EXTREME,$LIGHT_CAVALRY);
        let qtdArcherHorseback = calcSingleCollect($EXTREME,$ARCHER_HORSEBACK);
        let qtdHeavyCavalry = calcSingleCollect($EXTREME,$HEAVY_CAVALRY);
        $('#lancer-row .collect-extreme').text(qtdLancer);
        $('#swordsman-row .collect-extreme').text(qtdSwordsman);
        $('#barbarian-row .collect-extreme').text(qtdBarbarian);
        $('#archer-row .collect-extreme').text(qtdArcher);
        $('#light-cavalry-row .collect-extreme').text(qtdLightCavalry);
        $('#archer-horseback-row .collect-extreme').text(qtdArcherHorseback);
        $('#heavy-cavalry-row .collect-extreme').text(qtdHeavyCavalry);
    }
}

$LITTLE.on('change', function(){
    verifyColumn($LITTLE);
});

$MEDIUM.on('change', function(){
    verifyColumn($MEDIUM);
});

$BIG.on('change', function(){
    verifyColumn($BIG);
});

$EXTREME.on('change', function(){
    verifyColumn($EXTREME);
});

$LANCER.on('input', function(){
    calcCollect();
});
$SWORDSMAN.on('input', function(){
    calcCollect();
});
$BARBARIAN.on('input', function(){
    calcCollect();
});
$ARCHER.on('input', function(){
    calcCollect();
});
$LIGHT_CAVALRY.on('input', function(){
    calcCollect();
});
$ARCHER_HORSEBACK.on('input', function(){
    calcCollect();
});
$HEAVY_CAVALRY.on('input', function(){
    calcCollect();
});
