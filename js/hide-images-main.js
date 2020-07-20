window.addEventListener('load', (event) => {
  
  let buttons = document.querySelectorAll('.timer')

  buttons.forEach((button) => {
    button.addEventListener('click', imageTimer);
  });



  function imageTimer(){
    const parent = this.parentElement;
    console.log(parent)
    const time = parent.dataset.show;
    console.log(time)
    const img = parent.querySelector('img');
    const progressBar = parent.querySelector('.progress-bar');
    const finished = parent.querySelector('.finished')
    console.log(progressBar)
    img.classList.remove('hide')
    setTimeout(function () {
        img.classList = 'done';
        finished.style.display = 'block';
      }, (time*1000)); 
    progressBar.style.transition = 'width '+time+'s linear' 
    progressBar.style.width = '100%'

  }

});
