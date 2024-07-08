import { Component } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { ReservasService } from 'src/app/services/reservas.service';
import { format } from 'date-fns'; // Importa la función format de date-fns
import { MatSnackBar } from '@angular/material/snack-bar';
@Component({
  selector: 'app-create-reservas',
  templateUrl: './create-reservas.component.html',
  styleUrls: ['./create-reservas.component.css']
})
export class CreateReservasComponent {

  fechaInicio = new FormControl(format(new Date(), 'yyyy-MM-dd'), [Validators.required]); // Formatea la fecha de inicio
  horaInicio = new FormControl('', [Validators.required]);
  fechaFin = new FormControl(format(new Date(), 'yyyy-MM-dd'), [Validators.required]); // Formatea la fecha de fin
  horaFin = new FormControl('', [Validators.required]);
  fkUsuario = new FormControl(1, [Validators.required]);
  fkLaboratorio = new FormControl(1, [Validators.required]);
  descripcion = new FormControl('',[Validators.required]);


  reservasForm = new FormGroup({
    fechaInicio:this.fechaInicio,
    horaInicio:this.horaInicio,
    fechaFin:this.fechaFin,
    horaFin:this.horaFin,
    fkUsuario:this.fkUsuario,
    fkLaboratorio:this.fkLaboratorio,
    descripcion:this.descripcion
  });

  constructor(private reservasService:ReservasService,
              private router:Router,
              private activatedRoute:ActivatedRoute,
              private _snackBar: MatSnackBar
  ){

  }

  openSnackBar(message: string, action: string) {
    this._snackBar.open(message, action,{
      duration:3000
    });
  }

  dataUsuario:any=[
    {

    }
  ];
  dataLaboratorio:any=[];

  idReserva:any;

  dataReserva:any;

  ngOnInit(): void {
    this.idReserva = this.activatedRoute.snapshot.params['id'];
    if (this.idReserva){
      this.reservasService.getByIdReserva(this.idReserva).subscribe(
        (data:any)=>{          
          this.dataReserva = data.data;
          console.log(this.dataReserva);
          this.loaData();
        },
        error=>{
          this.openSnackBar(error.error.message,'Cerrar')
        }
      )      
    }
    this.getUsuario();
    this.getLaboratorio();
  }

  loaData(){
    if (this.dataReserva){
      this.dataReserva
      this.fechaInicio.setValue(this.dataReserva.fechaInicio);
      this.horaInicio.setValue(this.dataReserva.horaInicio);
      this.fechaFin.setValue(this.dataReserva.fechaFin);
      this.horaFin.setValue(this.dataReserva.horaFin);
      this.fkUsuario.setValue(this.dataReserva.fkUsuario);
      this.fkLaboratorio.setValue(this.dataReserva.fkLaboratorio);
      this.descripcion.setValue(this.dataReserva.descripcion);
    }
  }

  getUsuario(){
    this.reservasService.getUsuarios().subscribe((data:any)=>{
      this.dataUsuario = data.data;
      if (!this.idReserva)
      {
        this.fkUsuario.setValue(this.dataUsuario[0].idUsuario);
      }      
      
    },
    error=>{
      console.log(error);
    })
  }

  getLaboratorio(){
    this.reservasService.getLaboratorios().subscribe((data:any)=>{

      this.dataLaboratorio = data.data;
      if (!this.idReserva)
      {
        this.fkLaboratorio.setValue(this.dataLaboratorio[0].idLaboratorio);
      }
    },
    error=>{
      console.log(error);
    })
  }

  save(){

    if(!this.reservasForm.valid){
      this.reservasForm.markAllAsTouched();
      this.openSnackBar('Debe digitar todos los campos','Cerrar');
      return;
    }

    console.log(this.reservasForm.value)
    if (this.idReserva){
      this.reservasService.putReserva(this.idReserva,this.reservasForm.value).subscribe((data:any)=>{
        this.openSnackBar('Datos Almacenados Correcatemente','Cerrar');
        this.router.navigate(['./']);
      },
      error=>{
        this.openSnackBar(error.error.message,'Cerrar')
      });
    }
    else{
      this.reservasService.postReservas(this.reservasForm.value).subscribe((data:any)=>{
        this.openSnackBar('Datos Almacenados Correcatemente','Cerrar');
        this.router.navigate(['./']);
      },
      error=>{
        this.openSnackBar(error.error.message,'Cerrar')
      });
    }
  }

  cancel(){
    this.router.navigate(['./']);
  }

}
