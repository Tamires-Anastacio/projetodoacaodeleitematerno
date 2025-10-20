import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

export default function Restrita() {
  const [user,setUser] = useState(null);
  const navigate = useNavigate();

  useEffect(()=>{
    const checkSession = async ()=>{
      const res = await fetch("http://localhost/projetodoacaodeleitematerno/backend/session.php",{credentials:"include"});
      const data = await res.json();
      if(!data.loggedIn) navigate("/login");
      else setUser(data.user);
    };
    checkSession();
  },[]);

  if(!user) return <p>Carregando...</p>;
  return <h1>Bem-vindo, {user.nome}!</h1>;
}
