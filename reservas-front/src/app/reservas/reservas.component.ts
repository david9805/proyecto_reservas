import { Component } from '@angular/core';
import { FormControl } from '@angular/forms';
import { PageEvent } from '@angular/material/paginator';
import { MatTableDataSource } from '@angular/material/table';
import { ReservasService } from '../services/reservas.service';
import { MatSnackBar } from '@angular/material/snack-bar';

@Component({
  selector: 'app-reservas',
  templateUrl: './reservas.component.html',
  styleUrls: ['./reservas.component.css']
})
export class ReservasComponent {

  name = new FormControl('');

  dataReservas:any = [];


  dataSource = new MatTableDataSource<any>(this.dataReservas);

  displayedColumns = [
    'id','fechaInicio','fechaFin','laboratorio','solicitante','actions'
  ]

  pageSize = 8;
  pageIndex = 0;
  totalData = 0;

  constructor(private reservasService:ReservasService,
              private _snackBar:MatSnackBar
  ){

  }
  ngOnInit(): void {
    this.getReservas();
    
  }
  openSnackBar(message: string, action: string) {
    this._snackBar.open(message, action,{
      duration:3000
    });
  }
  getReservas(){
    const queryParams = {
      page:this.pageIndex,
      pageElements:this.pageSize
    }
    this.reservasService.getAllReservas(queryParams).subscribe((data:any)=>{
      console.log(data.data);
      this.dataReservas = data.data;
      this.dataSource = new MatTableDataSource<any>(this.dataReservas);
      this.totalData = data.total;
    },
    error=>{
      console.log(error);
    })
  }

  onPageChange(event: PageEvent) {
    if (this.pageSize != event.pageSize) {
      this.pageSize = event.pageSize;
      this.pageIndex = 0;
    } else {
      this.pageIndex = event.pageIndex;
    }
    this.getReservas();
  }

  delete(id:string){
    this.reservasService.deleteReserva(id).subscribe(
      (data:any)=>{
        this.openSnackBar('Registro eliminado correctamente','Cerrar');
        this.getReservas();
      },
      error=>{
        this.openSnackBar(error.error.message,'Cerrar')
      }
    )
  }

  
}
