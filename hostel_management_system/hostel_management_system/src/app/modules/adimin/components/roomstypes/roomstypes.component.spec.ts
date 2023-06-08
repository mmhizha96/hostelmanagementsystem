import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RoomstypesComponent } from './roomstypes.component';

describe('RoomstypesComponent', () => {
  let component: RoomstypesComponent;
  let fixture: ComponentFixture<RoomstypesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RoomstypesComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RoomstypesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
