var toastLiveExample = document.getElementById('liveToast')

if (toastLiveExample) {
    toastLiveExample.classList.add('show');
    setTimeout(() => {toastLiveExample.classList.remove('show');} , 3000)
}