window.onload = function() {
    //Select all the textarea with maxlength attribute
    var inputsWithLimit = document.querySelectorAll('textarea[maxlength]');
    if(inputsWithLimit.length > 0){
        inputsWithLimit.forEach(function(inputWithLimit){
            var hint = document.createElement('div');
            hint.style.color = "#D93600";
            inputWithLimit.parentNode.insertBefore(hint, inputWithLimit.nextSibling); //Insert Div right after the input field
            inputWithLimit.addEventListener('input', function(event){
            //Get the value from maxlength attribute 
            var inputMaxLength = event.target.getAttribute('maxlength');
            //append the message in the div tag
            hint.innerHTML = 'Total Characters : ' + inputMaxLength +
 ' Remaining : ' + (inputMaxLength - event.target.value.length);
            });
        });
    }
}