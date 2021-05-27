let operationWeight = 0;
const resourceTypes = {
    WOOD: 'wood',
    STONE: 'stone',
    IRON: 'iron',
    RESOURCE: 'res',
};
const collectTypesEnum = {
    LITTLE: 'little',
    MEDIUM: 'medium',
    BIG: 'big',
    EXTREME: 'extreme'
};
const unityTypes = [
    {
        name: 'lancer',
        capacity: 25,
        qtdToCollect: 0,
        qtdDistributed: 0
    },
    {
        name: 'swordsman',
        capacity: 15,
        qtdToCollect: 0,
        qtdDistributed: 0
    },
    {
        name: 'barbarian',
        capacity: 10,
        qtdToCollect: 0,
        qtdDistributed: 0
    },
    {
        name: 'archer',
        capacity: 10,
        qtdToCollect: 0,
        qtdDistributed: 0
    },
    {
        name: 'light-cavalry',
        capacity: 80,
        qtdToCollect: 0,
        qtdDistributed: 0
    },
    {
        name: 'archer-horseback',
        capacity: 10,
        qtdToCollect: 0,
        qtdDistributed: 0
    },
    {
        name: 'heavy-cavalry',
        capacity: 50,
        qtdToCollect: 0,
        qtdDistributed: 0
    },
];
const collectTypes = [
    {
        name: collectTypesEnum.LITTLE,
        withdrawPercent: 0.1,
        weight: 15
    },
    {
        name: collectTypesEnum.MEDIUM,
        withdrawPercent: 0.25,
        weight: 6
    },
    {
        name: collectTypesEnum.BIG,
        withdrawPercent: 0.5,
        weight: 3
    },
    {
        name: collectTypesEnum.EXTREME,
        withdrawPercent: 0.75,
        weight: 2
    },
];

$(document).ready(function(){
    enableTable();
    activeChangeCollectCheckbox();
    activeOnInputUnityTypesInput();
});

function enableTable(){
    for (const collectType of collectTypes) {
        verifyColumn($('#'+collectType.name));
    }
}

function updateOperationWeight(){
    operationWeight = 0;
    for (const collectType of collectTypes) {
        let $COLLECT = $('#'+collectType.name);
        if($COLLECT.prop('checked')){
            operationWeight += parseFloat($COLLECT.val());
        }
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

function calcSingleCollect(unity, collect){
    if(collect.name === collectTypesEnum.EXTREME){
        return unity.qtdToCollect-unity.qtdDistributed;
    }
    return Math.round(collect.weight*unity.qtdToCollect/operationWeight);
}

function activeChangeCollectCheckbox(){
    for (const collectType of collectTypes) {
        let $COLLECT = $('#'+collectType.name);
        $COLLECT.on('change', function(){
            verifyColumn($COLLECT);
        });
    }
}

function activeOnInputUnityTypesInput(){
    for (const unityType of unityTypes) {
        let $UNITY = $('#'+unityType.name);
        $UNITY.on('input', function(){
            calcCollect();
        });
    }
}

function calcCollect(){
    resetUnits();
    for (const collectType of collectTypes) {
        if($(`#${collectType.name}`).prop('checked')){
            let qtdTotalResources = 0;
            for (const unityType of unityTypes) {
                unityType.qtdToCollect = getInputValueFloat($(`#${unityType.name}`));
                let qtdUnity = calcSingleCollect(unityType, collectType);
                unityType.qtdDistributed += qtdUnity;
                $(`#${unityType.name}-row .collect-${collectType.name}`).text(qtdUnity);
                qtdTotalResources += qtdUnity*unityType.capacity*collectType.withdrawPercent;
            }
            qtdTotalResources = Math.round(qtdTotalResources);
            let eachResource = Math.round(qtdTotalResources/3);
            let diff = (eachResource*3) - qtdTotalResources;
            $(`#resources-row .collect-${collectType.name}`).html(`
            ${makeIconResource(resourceTypes.WOOD)} ${eachResource}<br>
            ${makeIconResource(resourceTypes.STONE)} ${eachResource}<br>
            ${makeIconResource(resourceTypes.IRON)} ${eachResource+diff}<br>
            ${makeIconResource(resourceTypes.RESOURCE)} ${qtdTotalResources}
            `);
        }
    }
}

function resetUnits(){
    for (const unityType of unityTypes) {
        unityType.qtdDistributed = 0;
        unityType.qtdToCollect = 0;
    }
}

function makeIconResource(type){
    return `<span class="icon header ${type}"></span>`
}
