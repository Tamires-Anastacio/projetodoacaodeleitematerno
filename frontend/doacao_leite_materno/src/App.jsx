import 'bootstrap/dist/css/bootstrap.min.css';
import React, { useState } from "react";
import "./App.css";



function App() {
  const [form, setForm] = useState({
    nome: "",
    cpf: "",
    fone: "",
    datanascimento: "",
    email: "",
    senha: "",
    uf: ""
  });
  const [mensagem, setMensagem] = useState("");

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const res = await fetch("http://localhost:8000/cadastro.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(form)
      });

      const data = await res.json();
      setMensagem(data.message);

    } catch (err) {
      setMensagem("Erro de conexão com o servidor.");
    }
  };

  return (
         <H1 className="protest-guerrilla-regular">Cadastro</H1>
         <div className='container'>

       
                <form action="cadastro.php" method="post" >
                    <label className="comic-relief-regular" for="nome">Nome Completo</label>
                    <input type="text" name="nome" id="nome" required >

                    <label className="comic-relief-regular" for="cpf">CPF </label>
                    <input type="text" name="cpf" id="cpf"  required >

                    <label className="comic-relief-regular" for="telefone">Telefone</label>
                    <input type="tel" name="telefone" id="fone" maxlength="15" placeholder="(xx) xxxxx-xxxx"required >
                    
                     <div className="mid">
                        <div class="col">
                            <label class="comic-relief-regular" for="datanasc">Data de Nascimento</label>
                             <input type="date" name="datanascimento" id="datanasc" required >
                        </div>
                     </div>   
                      <div className="col">
                            <label class="comic-relief-regular" for="uf">UF</label>
                            <select className="form-select"  id="uf" name="uf" required>
                                  <option value="">Selecione</option>
                                  <option value="AC">AC</option>
                                  <option value="AL">AL</option>
                                  <option value="AP">AP</option>
                                  <option value="AM">AM</option>
                                  <option value="BA">BA</option>
                                  <option value="CE">CE</option>
                                  <option value="DF">DF</option>
                                  <option value="ES">ES</option>
                                  <option value="GO">GO</option>
                                  <option value="MA">MA</option>
                                  <option value="MT">MT</option>
                                  <option value="MS">MS</option>
                                  <option value="MG">MG</option>
                                  <option value="PA">PA</option>
                                  <option value="PB">PB</option>
                                  <option value="PR">PR</option>
                                  <option value="PE">PE</option>
                                  <option value="PI">PI</option>
                                  <option value="RJ">RJ</option>
                                  <option value="RN">RN</option>
                                  <option value="RS">RS</option>
                                  <option value="RO">RO</option>
                                  <option value="RR">RR</option>
                                  <option value="SC">SC</option>
                                  <option value="SP">SP</option>
                                  <option value="SE">SE</option>
                                  <option value="TO">TO</option>
                           </select>
                        </div>
                         
                     </div>
               </form>
        </div>        
      <p>{mensagem}</p>
    </div>
  );
}

export default App;
