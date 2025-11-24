const chatMessages = document.getElementById('chatMessages');
const userInput = document.getElementById('userInput');

// Banco de respostas simuladas
const respostas = [
    {
        keywords: ['olá', 'oi', 'bom dia', 'boa tarde', 'boa noite'],
        resposta: 'Olá! 😊 Sou o chat de dúvidas sobre doação de leite materno. Como posso ajudar você hoje?'
    },
    {
        keywords: ['como doar', 'doar leite', 'quero doar'],
        resposta: 'Você pode doar leite materno entrando em contato com uma instituição parceira ou preenchendo o formulário de doação em nosso site.'
    },
    {
        keywords: ['quem pode doar', 'requisitos', 'condições'],
        resposta: 'Mães saudáveis que estejam amamentando podem doar leite. Evite doar se estiver com doenças infecciosas ou tomando certos medicamentos.'
    },
    {
        keywords: ['onde doar', 'local', 'onde',],
        resposta: ["Ligue para o Disque Saúde (136) e peça informações sobre doação de leite humano.", "Ou procure no site da secretaria de saúde do seu estado ou município por “Banco de Leite Humano”."]
    },
    {
        keywords: ['contato', 'telefone', 'email'],
        resposta: 'Você pode entrar em contato pelo telefone (XX) XXXX-XXXX ou pelo email contato@instituicao.org.'
    },
    {
        keywords: ['horário', 'abertura', 'funciona'],
        resposta: 'As instituições normalmente funcionam de segunda a sexta, das 8h às 17h. Consulte a unidade local para confirmar.'
    }
];

// Envia a mensagem do usuário
function sendMessage() {
    const text = userInput.value.trim();
    if (!text) return;

    addMessage('user', text);
    userInput.value = '';

    setTimeout(() => {
        const resposta = getResposta(text);
        addMessage('institution', resposta);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }, 700);
}

// Adiciona mensagem no chat
function addMessage(sender, text) {
    const div = document.createElement('div');
    div.classList.add('message', sender);
    div.innerHTML = `<div class="text">${text}</div>`;
    chatMessages.appendChild(div);
}

// Procura resposta baseada em palavras-chave
function getResposta(text) {
    const lower = text.toLowerCase();
    for (const item of respostas) {
        if (item.keywords.some(kw => lower.includes(kw))) {
            return item.resposta;
        }
    }
    return 'Desculpe, não entendi sua dúvida. Por favor, tente reformular ou entre em contato com a instituição.';
}

// Permite enviar ao pressionar Enter
userInput.addEventListener('keypress', e => {
    if (e.key === 'Enter') sendMessage();
});
