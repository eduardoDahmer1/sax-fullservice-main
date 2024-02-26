var iconPerson = document.querySelector('.bi-person-fill')
var iconTruck = document.querySelector('.bi-truck')
var isTwo = ''
var iconCard = document.querySelector('.bi-credit-card')

document.addEventListener('DOMContentLoaded', function () {
  const steps = document.querySelectorAll('.step');
  let currentStep = 0;

  function showStep(stepIndex) {
    steps.forEach((step, index) => {
      step.classList.toggle('active', index === stepIndex);
    });
  }

  function nextStep() {
    currentStep += 1;
    if (currentStep < steps.length) {
      if(currentStep == 1) {
        iconPerson.classList.add('color-2')
        iconPerson.parentNode.previousElementSibling.classList.add('bg-color-2')
      }
      if (currentStep == 2) {
        iconTruck.classList.add('color-2')
        iconTruck.parentNode.previousElementSibling.classList.add('bg-color-2')
      }
      if (currentStep == 3) {
        iconCard.classList.add('color-2')
        iconCard.parentNode.previousElementSibling.classList.add('bg-color-2')
      }
      showStep(currentStep);
    } else {
      currentStep = steps.length - 1;
    }
  }

  function prevStep() {
    currentStep -= 1;
    if (currentStep == 2) {
      iconCard.classList.remove('color-2')
      iconCard.parentNode.previousElementSibling.classList.remove('bg-color-2')
    }
    if (currentStep == 1) {
      iconTruck.classList.remove('color-2')
      iconTruck.parentNode.previousElementSibling.classList.remove('bg-color-2')
    }
    if (currentStep == 0) {
      iconPerson.classList.remove('color-2')
      iconPerson.parentNode.previousElementSibling.classList.remove('bg-color-2')
    }
    if (currentStep >= 0) {
      showStep(currentStep);
    } else {
      currentStep = 0;
    }
  }

  var btnContinue = document.querySelectorAll('.btn-continue')
  var btnPrev = document.querySelectorAll('.btn-back')
  var personalInputs = document.querySelectorAll('.required-input');
  var newAddressDiv = document.querySelector('.new-address')
  var newAddressInputs = newAddressDiv.querySelectorAll('input')
  var address =  document.getElementById('address')
  var shippingCity =  document.getElementById('shippingCity')
  var shippingState =  document.getElementById('shippingState')

    btnContinue.forEach(btn => {
      btn.addEventListener('click', () => {
        if (currentStep === 1) {
          let allInputsFilled = true;
    
          personalInputs.forEach((input) => {
            if (input.value === '') {
              allInputsFilled = false
            }
          });
    
          if (allInputsFilled) {
            nextStep();
          } else {
            personalInputs.forEach((input) => {
              if (input.value === '') {
                input.classList.add('border-danger-subtle')
              }
            });
            alert('Por favor, preencha todos os campos antes de continuar.')
          }
        } 
        else if (currentStep === 2 && isTwo.checked) {
          if(address.value === '' || shippingCity.value === '' || shippingState.value === '') {
              if(address.value === '') {
                address.classList.add('border-danger-subtle')
              }
              if(shippingCity.value === '') {
                shippingCity.classList.add('border-danger-subtle')
              }
              if(shippingState.value === '') {
                shippingState.classList.add('border-danger-subtle')
              }
              alert('Por favor, preencha todos os campos antes de continuar.')
          } else {
            nextStep();
            freteText.classList.add('d-none')
          }
        }
        else {
          nextStep();
        }
      });
    });
    address.addEventListener('change', () => {
      address.classList.remove('border-danger-subtle')
    })
    shippingCity.addEventListener('change', () => {
      shippingCity.classList.remove('border-danger-subtle')
    })
    shippingState.addEventListener('change', () => {
      shippingState.classList.remove('border-danger-subtle')
    })
    personalInputs.forEach((input) => {
      input.addEventListener('change', () => {
        input.classList.remove('border-danger-subtle')
      })
    });
    newAddressInputs.forEach((input) => {
      input.addEventListener('change', () => {
        input.classList.remove('border-danger-subtle')
      })
    });
    btnPrev.forEach(btn => {
      btn.addEventListener('click', prevStep);
    })

    showStep(currentStep);
  });

  var selectLocal = document.querySelector('.select-local')
  var cdeMap = document.querySelector('.CDE-MAP')
  var asuncion = document.querySelector('.ASUNCION-MAP')
  var frete = document.getElementById('freteGratis')
  var frete10 = document.getElementById('frete10')
  var freteText = document.getElementById('freteText')
  var shippingType = document.querySelectorAll('input[name="shipping"]')
  var newAddress = document.querySelector('.new-address')
  var myaddress = document.querySelector('#inMyAddress')
  var isTwo = document.getElementById('newaddress')
  var remove10 = document.querySelector('.price-10')
  var add10 = document.querySelector('.add10')
  var saxFrete = document.querySelector('.primeSax')

  shippingType.forEach(input => {
    window.addEventListener('load', function() {
      if(input.checked && input.value == 1) {
        myaddress.previousElementSibling.classList.replace('d-flex', 'd-none')
        myaddress.classList.remove('d-none')
        freteText.nextElementSibling.classList.replace('d-none', 'd-block')
        newAddress.classList.add('d-none')
        saxFrete.classList.replace('d-none', 'd-block')
      }
      if(input.checked && input.value == 2) {
        saxFrete.classList.replace('d-none', 'd-block')
        newAddress.classList.replace('d-none', 'd-block')
        frete.classList.add('d-none')
        frete10.classList.remove('d-none')
        selectLocal.classList.replace('d-flex', 'd-none')
        freteText.nextElementSibling.classList.replace('d-block', 'd-none')
        cdeMap.classList.replace('d-flex', 'd-none')
        asuncion.classList.replace('d-flex', 'd-none')
        remove10.classList.replace('d-block', 'd-none')
        add10.classList.replace('d-none', 'd-block')
      }
    });
    input.addEventListener('change', () => {
      if(input.value == 1) {
        saxFrete.classList.replace('d-none', 'd-block')
        newAddress.classList.replace('d-none', 'd-block')
        frete.classList.add('d-none')
        frete10.classList.remove('d-none')
        selectLocal.classList.replace('d-flex', 'd-none')
        freteText.nextElementSibling.classList.replace('d-block', 'd-none')
        cdeMap.classList.replace('d-flex', 'd-none')
        asuncion.classList.replace('d-flex', 'd-none')
        remove10.classList.replace('d-block', 'd-none')
        freteText.classList.replace('d-block', 'd-none')
        add10.classList.replace('d-none', 'd-block')
      }
      if(input.value == 2) {
        saxFrete.classList.replace('d-none', 'd-block')
        newAddress.classList.replace('d-none', 'd-block')
        frete.classList.add('d-none')
        frete10.classList.remove('d-none')
        selectLocal.classList.replace('d-flex', 'd-none')
        freteText.classList.replace('d-block', 'd-none')
        freteText.nextElementSibling.classList.replace('d-block', 'd-none')
        cdeMap.classList.replace('d-flex', 'd-none')
        asuncion.classList.replace('d-flex', 'd-none')
        remove10.classList.replace('d-block', 'd-none')
        add10.classList.replace('d-none', 'd-block')
      }
      if(input.value == 3) {
        newAddress.classList.replace('d-block', 'd-none')
        saxFrete.classList.replace('d-block', 'd-none')
        remove10.classList.replace('d-none', 'd-block')
        add10.classList.add('d-none')
        selectLocal.classList.replace('d-none', 'd-flex')
        cdeMap.classList.replace('d-none', 'd-flex')
        frete.classList.remove('d-none')
        frete10.classList.add('d-none')
        freteText.classList.replace('d-none', 'd-block')
        frete.innerText = "FREE"
        freteText.nextElementSibling.classList.replace('d-none', 'd-block')
      }
    })
  })
  selectLocal.addEventListener('change', () => {
    if(selectLocal.value == 1) {
      cdeMap.classList.replace('d-none', 'd-flex')
      asuncion.classList.replace('d-flex', 'd-none')
      freteText.classList.replace('d-none', 'd-block')
      freteText.nextElementSibling.innerText = " CDE"
    } else {
      cdeMap.classList.replace('d-flex', 'd-none')
      asuncion.classList.replace('d-none', 'd-flex')
      freteText.classList.replace('d-none', 'd-block')
      freteText.nextElementSibling.innerText = " Asunción"
    }
  })
  var payType = document.querySelectorAll('input[name="pay-method"]')
  var formBank = document.querySelector('.replace-bank')
  var dataDeposit = document.querySelector('.data-deposit')
  var btnDeposit = document.querySelector('.btn-deposit')
  var dataDeposit2 = document.querySelector('.data-deposit2')

  function replaceBank(newAction) {
    formBank.action = newAction;
  }
      
  payType.forEach(type => {
    type.addEventListener('change', () => {
      if(type.value == 1) {
        dataDeposit.classList.replace('d-none', 'd-block')
        dataDeposit2.classList.replace('d-none', 'd-block')
        btnDeposit.style.bottom = '-60px'
        formBank.setAttribute("id", "myform")
        replaceBank(type.getAttribute('data-form'))
        type.nextElementSibling.classList.add('color-2')
        type.nextElementSibling.querySelector('i').classList.add('color-2')
        type.parentNode.classList.add('color-2')
        payType[0].nextElementSibling.querySelector('i').classList.remove('color-2')
        payType[0].nextElementSibling.classList.remove('color-2')
        payType[0].parentNode.classList.remove('color-2')
      }
      if(type.value == 2) {
        dataDeposit.classList.replace('d-block', 'd-none')
        dataDeposit2.classList.replace('d-block', 'd-none')
        btnDeposit.style.bottom = '30px'
        formBank.setAttribute("id", "bancard-form")
        replaceBank(type.getAttribute('data-form'))
        type.nextElementSibling.classList.add('color-2')
        type.nextElementSibling.querySelector('i').classList.add('color-2')
        type.parentNode.classList.add('color-2')
        payType[1].nextElementSibling.querySelector('i').classList.remove('color-2')
        payType[1].nextElementSibling.classList.remove('color-2')
        payType[1].parentNode.classList.remove('color-2')
      }
    })
  })