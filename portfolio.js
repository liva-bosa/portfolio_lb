import emailjs from '@emailjs/browser';
emailjs.init("37Zk2vB5_SF7pV1Fu");

var templateParams = {
    name: 'Līva',
    notes: 'Jauna ziņa',
  };
  

emailjs.send('service_eqt20ra', 'template_96jhzai', templateParams).then(
    (response) => {
      console.log('SUCCESS!', response.status, response.text);
    },
    (error) => {
      console.log('FAILED...', error);
    },
  );