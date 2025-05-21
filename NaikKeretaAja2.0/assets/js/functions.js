  function openForm(id) {
    document.getElementById(id).classList.remove('hidden');
  }

  function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
  }

  function switchModal(closeId, openId) {
    closeModal(closeId);
    openForm(openId);
  }