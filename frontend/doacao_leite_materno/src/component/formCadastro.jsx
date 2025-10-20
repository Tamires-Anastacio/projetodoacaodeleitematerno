import React, { useState } from "react";
import { Button, Form } from "react-bootstrap";



function FormCad() {
  const [formData, setFormData] = useState({
    nome: "",
    cpf: "",
    telefone: "",
    datanascimento: "",
    uf: "",
    cidade: "",
    email: "",
    senha: "",
    confsenha: "",
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (formData.senha !== formData.confsenha) {
      alert("As senhas não coincidem!");
      return;
    }

    const payload = {
      nome: formData.nome,
      cpf: formData.cpf,
      fone: formData.telefone,
      datanascimento: formData.datanascimento,
      email: formData.email,
      senha: formData.senha,
      uf: formData.uf,
      cidade: formData.cidade,
    };

    try {
      const response = await fetch(
        "http://localhost/projetodoacaodeleitematerno/backend/cadastro.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(payload),
        }
      );

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      alert(data.message);

      if (data.success) {
        setFormData({
          nome: "",
          cpf: "",
          telefone: "",
          datanascimento: "",
          uf: "",
          cidade: "",
          email: "",
          senha: "",
          confsenha: "",
        });
      }
    } catch (error) {
      console.error("Erro ao enviar:", error);
      alert("Erro ao enviar os dados: " + error.message);
    }
  };

  return (
    <div className="teses">
      
      <Form onSubmit={handleSubmit}>
      <h1 className="protest-guerrilla-regular mb-4">Cadastro</h1>
        <Form.Group className="mb-3">
          <Form.Label>Nome Completo</Form.Label>
          <Form.Control
            type="text"
            name="nome"
            value={formData.nome}
            onChange={handleChange}
            required
          />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label>CPF</Form.Label>
          <Form.Control
            type="text"
            name="cpf"
            maxLength="12"
            value={formData.cpf}
            onChange={handleChange}
            required
          />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label>Telefone</Form.Label>
          <Form.Control
            type="tel"
            name="telefone"
            maxLength="15"
            placeholder="(xx) xxxxx-xxxx"
            value={formData.telefone}
            onChange={handleChange}
            required
          />
        </Form.Group>
        <div className="mid">
        <Form.Group className="mb-3">
          <Form.Label>Data de Nascimento</Form.Label>
          <Form.Control
            type="date"
            name="datanascimento"
            value={formData.datanascimento}
            onChange={handleChange}
            required
          />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label>UF</Form.Label>
          <Form.Select
            name="uf"
            value={formData.uf}
            onChange={handleChange}
            required
          >
            <option value="">Selecione</option>
            <option value="SP">SP</option>
            <option value="RJ">RJ</option>
            {/* Adicione os outros estados aqui */}
          </Form.Select>
        </Form.Group>


        </div>
        

        <Form.Group className="mb-3">
          <Form.Label>Cidade</Form.Label>
          <Form.Control
            type="text"
            name="cidade"
            value={formData.cidade}
            onChange={handleChange}
            required
          />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label>Email</Form.Label>
          <Form.Control
            type="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            required
          />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label>Senha</Form.Label>
          <Form.Control
            type="password"
            name="senha"
            value={formData.senha}
            onChange={handleChange}
            required
          />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label>Confirme sua Senha</Form.Label>
          <Form.Control
            type="password"
            name="confsenha"
            value={formData.confsenha}
            onChange={handleChange}
            required
          />
        </Form.Group>

        <Button variant="success" type="submit" className="w-100">
          Enviar
        </Button>
      </Form>
    </div>
  );
}

export default FormCad;
